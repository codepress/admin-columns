<?php

namespace AC\ListTable;

use WP_List_Table;

// TODO remove usages
trait WpListTableTrait
{

    /**
     * @var WP_List_Table $table
     */
    protected $table;

    public function get_total_items(): int
    {
        return $this->table->get_pagination_arg('total_items');
    }

}