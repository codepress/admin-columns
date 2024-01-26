<?php

declare(strict_types=1);

namespace AC;

use AC\ListScreenRepository\Sort;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use WP_User;

interface ListScreenRepository
{

    public function find(ListScreenId $id): ?ListScreen;

    public function exists(ListScreenId $id): bool;

    public function find_all(Sort $sort = null): ListScreenCollection;

    public function find_all_by_key(ListKey $key, Sort $sort = null): ListScreenCollection;

    public function find_all_by_assigned_user(ListKey $key, WP_User $user, Sort $sort = null): ListScreenCollection;

}