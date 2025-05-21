<?php

declare(strict_types=1);

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\Type\ListScreenId;
use AC\Type\ListScreenStatus;
use AC\Type\TableId;
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

    public function find_all_by_type(ListScreenStatus $type): ListScreenCollection
    {
        return (new Filter\ListScreenStatus($type))->filter(
            $this->find_all_from_source()
        );
    }

    abstract protected function find_all_from_source(): ListScreenCollection;

    public function find_all_by_table_id(
        TableId $table_id,
        Sort $sort = null,
        ListScreenStatus $type = null
    ): ListScreenCollection {
        return $this->sort(
            $this->find_all_by_table_id_from_source($table_id, $type),
            $sort
        );
    }

    abstract protected function find_all_by_table_id_from_source(
        TableId $table_id,
        ListScreenStatus $type = null
    ): ListScreenCollection;

    public function find_all_by_assigned_user(
        TableId $table_id,
        WP_User $user,
        Sort $sort = null,
        ListScreenStatus $type = null
    ): ListScreenCollection {
        $user_assigned_filter = new Filter\UserAssigned($user);

        return $user_assigned_filter->filter(
            $this->find_all_by_table_id($table_id, $sort, $type)
        );
    }

}