<?php

namespace AC\ListScreenRepository\Sort;

use AC\Storage\Repository\ListScreenOrder;
use AC\Storage\Repository\TableListOrder;
use AC\Type\TableId;
use WP_User;

class UserOrder extends ListIds
{

    public function __construct(WP_User $user, TableId $table_id)
    {
        parent::__construct($this->get_manual_sorted_list_ids($user, $table_id));
    }

    private function get_manual_sorted_list_ids(WP_User $user, TableId $table_id): array
    {
        $list_order_user = (new TableListOrder($user->ID))->get_order($table_id);
        $list_order = (new ListScreenOrder())->get($table_id);

        return array_unique(array_merge($list_order_user, $list_order));
    }
}