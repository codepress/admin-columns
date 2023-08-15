<?php

declare(strict_types=1);

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\Type\ListScreenId;
use WP_User;

trait ListScreenRepositoryTrait
{

    public function find(ListScreenId $id): ?ListScreen
    {
        return $this->find_from_source($id);
    }

    abstract protected function find_from_source(ListScreenId $id): ?ListScreen;

    public function exists(ListScreenId $id): bool
    {
        return null !== $this->find($id);
    }

    protected function sort(ListScreenCollection $collection, Sort $sort = null): ListScreenCollection
    {
        return $sort
            ? $sort->sort($collection)
            : $collection;
    }

    public function find_all(Sort $sort = null): ListScreenCollection
    {
        return $this->sort(
            $this->find_all_from_source(),
            $sort
        );
    }

    abstract protected function find_all_from_source(): ListScreenCollection;

    public function find_all_by_key(string $key, Sort $sort = null): ListScreenCollection
    {
        return $this->sort(
            $this->find_all_by_key_from_source($key),
            $sort
        );
    }

    abstract protected function find_all_by_key_from_source(string $key): ListScreenCollection;

    public function find_all_by_assigned_user(string $key, WP_User $user, Sort $sort = null): ListScreenCollection
    {
        $user_assigned_filter = new Filter\UserAssigned($user);

        return $this->sort(
            $user_assigned_filter->filter(
                $this->find_all_by_key($key)
            ),
            $sort
        );
    }

}