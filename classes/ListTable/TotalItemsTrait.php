<?php

namespace AC\ListTable;

use LogicException;
use WP_List_Table;

trait TotalItemsTrait
{

    public function get_total_items(): int
    {
        global $wp_list_table;

        if ( ! $wp_list_table instanceof WP_List_Table) {
            throw new LogicException('The $wp_list_table global is not an instance of WP_List_Table.');
        }

        return $wp_list_table->get_pagination_arg('total_items') ?? 0;
    }

}