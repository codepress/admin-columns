<?php

declare(strict_types=1);

namespace AC\ListScreenRepository;

use AC\ListScreenCollection;
use AC\ListScreenRepository;
use AC\Type\ListScreenId;
use AC\Type\ListScreenStatus;
use AC\Type\TableId;
use WP_User;

abstract class Base implements ListScreenRepository
{

    public function exists(ListScreenId $id): bool
    {
        return null !== $this->find($id);
    }

    protected function sort(ListScreenCollection $collection, ?Sort $sort = null): ListScreenCollection
    {
        return $sort
            ? $sort->sort($collection)
            : $collection;
    }

    public function find_all_by_assigned_user(
        TableId $table_id,
        WP_User $user,
        ?Sort $sort = null,
        ?ListScreenStatus $status = null
    ): ListScreenCollection {
        return (new Filter\UserAssigned($user))->filter(
            $this->find_all_by_table_id($table_id, $sort, $status)
        );
    }

}