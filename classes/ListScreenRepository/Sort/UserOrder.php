<?php

namespace AC\ListScreenRepository\Sort;

use AC\Storage\Model\ListScreenOrder;
use AC\Storage\Model\TableListOrder;
use AC\Type\ListKey;
use WP_User;

class UserOrder extends ListIds
{

    public function __construct(WP_User $user, ListKey $list_key)
    {
        parent::__construct($this->get_manual_sorted_list_ids($user, $list_key));
    }

    private function get_manual_sorted_list_ids(WP_User $user, ListKey $list_key): array
    {
        $list_order_user = (new TableListOrder($user->ID))->find($list_key) ?: [];
        $list_order = (new ListScreenOrder())->get($list_key);

        return array_unique(array_merge($list_order_user, $list_order));
    }
}