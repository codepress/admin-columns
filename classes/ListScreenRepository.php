<?php

declare(strict_types=1);

namespace AC;

use AC\ListScreenRepository\Sort;
use AC\Type\ListScreenId;
use AC\Type\ListScreenStatus;
use AC\Type\TableId;
use WP_User;

interface ListScreenRepository
{

    public function find(ListScreenId $id): ?ListScreen;

    public function exists(ListScreenId $id): bool;

    public function find_all(Sort $sort = null): ListScreenCollection;

    public function find_all_by_status(ListScreenStatus $type): ListScreenCollection;

    public function find_all_by_table_id(
        TableId $table_id,
        Sort $sort = null,
        ListScreenStatus $type = null
    ): ListScreenCollection;

    public function find_all_by_assigned_user(
        TableId $table_id,
        WP_User $user,
        Sort $sort = null
    ): ListScreenCollection;

}