<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Storage;

use AC;
use AC\Exception;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository\Rules;
use AC\ListScreenRepository\SourceAware;
use AC\Type\ListScreenId;
use LogicException;

class ListScreenRepository implements AC\ListScreenRepositoryWritable, SourceAware
{

    use AC\ListScreenRepository\ListScreenRepositoryTrait;

    private $repository;

    private $writable;

    private $rules;

    public function __construct(AC\ListScreenRepository $repository, bool $writable = null, Rules $rules = null)
    {
        if (null === $writable) {
            $writable = false;
        }

        $this->repository = $repository;
        $this->writable = $writable && $this->repository instanceof AC\ListScreenRepositoryWritable;
        $this->rules = $rules;
    }

    protected function find_from_source(ListScreenId $id): ?ListScreen
    {
        $list_screen = $this->repository->find($id);

        if ($list_screen && ! $this->is_writable()) {
            $list_screen->set_read_only(true);
        }

        return $list_screen;
    }

    protected function find_all_from_source(): ListScreenCollection
    {
        $list_screens = $this->repository->find_all();

        if ( ! $this->is_writable()) {
            $this->set_all_read_only($list_screens);
        }

        return $list_screens;
    }

    protected function find_all_by_key_from_source(string $key): ListScreenCollection
    {
        $list_screens = $this->repository->find_all_by_key($key);

        if ( ! $this->is_writable()) {
            $this->set_all_read_only($list_screens);
        }

        return $list_screens;
    }

    public function is_writable(): bool
    {
        return $this->writable;
    }

    public function with_writable(bool $writable): self
    {
        return new self(
            $this->repository,
            $writable,
            $this->rules
        );
    }

    public function get_rules(): Rules
    {
        if ( ! $this->has_rules()) {
            throw new LogicException('No rules defined.');
        }

        return $this->rules;
    }

    public function has_rules(): bool
    {
        return $this->rules !== null;
    }

    private function set_all_read_only(ListScreenCollection $list_screens): void
    {
        foreach ($list_screens as $list_screen) {
            $list_screen->set_read_only(true);
        }
    }

    public function exists(ListScreenId $id): bool
    {
        return $this->repository->exists($id);
    }

    public function save(ListScreen $list_screen): void
    {
        if ($this->repository instanceof AC\ListScreenRepositoryWritable) {
            $this->repository->save($list_screen);
        }
    }

    public function delete(ListScreen $list_screen): void
    {
        if ($this->repository instanceof AC\ListScreenRepositoryWritable) {
            $this->repository->delete($list_screen);
        }
    }

    public function get_source(ListScreenId $id = null): string
    {
        if ( ! $this->has_source($id)) {
            throw new Exception\SourceNotAvailableException();
        }

        return $this->repository->get_source($id);
    }

    public function has_source(ListScreenId $id = null): bool
    {
        return $this->repository instanceof SourceAware && $this->repository->has_source($id);
    }

}