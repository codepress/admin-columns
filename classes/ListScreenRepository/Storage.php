<?php

declare(strict_types=1);

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository;
use AC\ListScreenRepositoryWritable;
use AC\Type\ListScreenId;
use AC\Type\ListScreenStatus;
use AC\Type\TableId;
use LogicException;

final class Storage extends Base implements ListScreenRepositoryWritable
{

    /**
     * @var Storage\ListScreenRepository[]
     */
    private array $repositories = [];

    /**
     * @return Storage\ListScreenRepository[]
     */
    public function get_repositories(): array
    {
        return array_reverse($this->repositories);
    }

    public function set_repositories(array $repositories): void
    {
        foreach ($repositories as $repository) {
            if ( ! $repository instanceof ListScreenRepository\Storage\ListScreenRepository) {
                throw new LogicException('Expected a Storage\ListScreenRepository object.');
            }
        }

        $this->repositories = array_reverse($repositories);
    }

    public function with_repository(string $name, ListScreenRepository\Storage\ListScreenRepository $repository): self
    {
        $repositories = $this->get_repositories();
        $repositories[$name] = $repository;

        $storage = new self();
        $storage->set_repositories($repositories);

        return $storage;
    }

    public function has_repository($key): bool
    {
        return array_key_exists($key, $this->repositories);
    }

    public function get_repository($key): Storage\ListScreenRepository
    {
        if ( ! $this->has_repository($key)) {
            throw new LogicException(sprintf('Repository with key %s not found.', $key));
        }

        return $this->repositories[$key];
    }

    public function find(ListScreenId $id): ?ListScreen
    {
        foreach ($this->repositories as $repository) {
            $list_screen = $repository->get_list_screen_repository()->find($id);

            if ($list_screen) {
                $list_screen->set_read_only(! $repository->is_writable());

                return $list_screen;
            }
        }

        return null;
    }

    public function find_all(?Sort $sort = null): ListScreenCollection
    {
        $collection = new ListScreenCollection();

        foreach ($this->repositories as $repository) {
            foreach ($repository->get_list_screen_repository()->find_all() as $list_screen) {
                if ( ! $collection->contains($list_screen)) {
                    $list_screen->set_read_only(! $repository->is_writable());

                    $collection->add($list_screen);
                }
            }
        }

        return $this->sort($collection, $sort);
    }

    public function find_all_by_table_id(
        TableId $table_id,
        ?Sort $sort = null,
        ?ListScreenStatus $type = null
    ): ListScreenCollection {
        $collection = new ListScreenCollection();

        foreach ($this->repositories as $repository) {
            $list_screens = $repository->get_list_screen_repository()
                                       ->find_all_by_table_id($table_id, null, $type);

            foreach ($list_screens as $list_screen) {
                if ( ! $collection->contains($list_screen)) {
                    $list_screen->set_read_only(! $repository->is_writable());

                    $collection->add($list_screen);
                }
            }
        }

        return $this->sort($collection, $sort);
    }

    public function save(ListScreen $list_screen): void
    {
        $repository = $this->get_writable_repository($list_screen);

        if ($repository) {
            $repository->save($list_screen);
        }
    }

    public function delete(ListScreen $list_screen): void
    {
        foreach ($this->get_writable_repositories($list_screen) as $repository) {
            if ($repository->find($list_screen->get_id())) {
                $repository->delete($list_screen);
                break;
            }
        }
    }

    private function get_writable_repository(ListScreen $list_screen): ?ListScreenRepositoryWritable
    {
        return $this->get_writable_repositories($list_screen)[0] ?? null;
    }

    /**
     * @return ListScreenRepositoryWritable[]
     */
    private function get_writable_repositories(ListScreen $list_screen): array
    {
        $repositories = [];

        foreach ($this->repositories as $repository) {
            $match = true;

            if ($repository->has_rules()) {
                $match = $repository->get_rules()->match([
                    Rule::ID   => $list_screen->get_id(),
                    Rule::TYPE => (string)$list_screen->get_table_id(),
                ]);
            }

            if ($match && $repository->is_writable()) {
                $repositories[] = $repository->get_list_screen_repository();
            }
        }

        return $repositories;
    }

}