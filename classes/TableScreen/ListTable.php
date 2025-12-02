<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;

interface ListTable
{

    /**
     * Calling this method can initialize its corresponding WP list table, which can do all
     * the necessary setup, such as SQL queries to populate itself
     */
    public function list_table(): AC\ListTable;

}