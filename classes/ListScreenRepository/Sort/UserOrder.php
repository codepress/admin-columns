<?php

namespace AC\ListScreenRepository\Sort;

use AC\Storage\ListScreenOrder;
use AC\Storage\TableListOrder;
use WP_User;

class UserOrder extends ListIds
{

    public function __construct(WP_User $user, string $list_key)
    {
        parent::__construct($this->get_manual_sorted_list_ids($user, $list_key));
    }

    private function get_manual_sorted_list_ids(WP_User $user, string $list_key): array
    {
        $list_order_user = (new TableListOrder($user->ID))->get($list_key) ?: [];
        $list_order = (new ListScreenOrder())->get($list_key);

        return array_unique(array_merge($list_order_user, $list_order));
    }
}