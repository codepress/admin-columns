<?php

declare(strict_types=1);

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository;
use AC\ListScreenRepositoryWritable;
use AC\Type\ListScreenId;
use ACP\Search\SegmentRepository;
use ACP\Search\SegmentRepositoryAware;
use LogicException;

final class Storage implements ListScreenRepositoryWritable, SegmentRepositoryAware
{

    use ListScreenRepositoryTrait;

    /**
     * @var Storage\ListScreenRepository[]
     */
    private $repositories = [];

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

    protected function find_from_source(ListScreenId $id): ?ListScreen
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

    protected function find_all_from_source(): ListScreenCollection
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

        return $collection;
    }

    protected function find_all_by_key_from_source(string $key): ListScreenCollection
    {
        $collection = new ListScreenCollection();

        foreach ($this->repositories as $repository) {
            foreach ($repository->get_list_screen_repository()->find_all_by_key($key) as $list_screen) {
                if ( ! $collection->contains($list_screen)) {
                    $list_screen->set_read_only(! $repository->is_writable());

                    $collection->add($list_screen);
                }
            }
        }

        return $collection;
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

    public function get_segment_repository(ListScreen $list_screen): SegmentRepository
    {
        foreach( $this->get_repositories() as $repository ) {
            if ( $repository->get_list_screen_repository( $list_screen->get_id() ) ) {
                return $list_screen_repository->get_segment_repository( $list_screen );
            }
        }

        return null;
    }

    public function get_repository_by_list_screen( ListScreen $list_screen ) : ?ListScreenRepository {
        foreach( $this->repositories as $repository ) {
            if ( $repository->get_list_screen_repository()->find( $list_screen->get_id() ) ) {
                return $repository->get_list_screen_repository();
            }
        }

        return null;
    }



    // TODO David consider get_repository_by_list_screen( ListScreen $list_screen )?
    // TODO David maybe consider naming _by_list_screen
    // TODO David get_segment_repository( ListScreen $list_screen );
    // TODO David ALWAYS give a LS id or LS to use get_segment_repositoru. Chjange the interface
    // TODO inject via de repository? And on save, get the proper repo? So it is stored properly?

    // TODO is this still required?
    public function get_writable_repository(ListScreen $list_screen): ?ListScreenRepositoryWritable
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
                    Rule::ID    => $list_screen->has_id() ? $list_screen->get_id() : null,
                    Rule::TYPE  => $list_screen->get_key(),
                    Rule::GROUP => $list_screen->get_group(),
                ]);
            }

            if ($match && $repository->is_writable()) {
                $repositories[] = $repository->get_list_screen_repository();
            }
        }

        return $repositories;
    }

}