<?php

declare(strict_types=1);

namespace AC\TableScreen\TableRowsFactory;

use AC\TableScreen;
use AC\TableScreen\TableRows;
use AC\TableScreen\TableRowsFactory;

class BaseFactory implements TableRowsFactory
{

    public function create(TableScreen $table_screen): ?TableRows
    {
        switch (true) {
            case $table_screen instanceof TableScreen\Post :
                return new TableScreen\TableRows\Post($table_screen->list_table());
            case $table_screen instanceof TableScreen\Media :
                return new TableScreen\TableRows\Media($table_screen->list_table());
            case $table_screen instanceof TableScreen\Comment :
                return new TableScreen\TableRows\Comment($table_screen->list_table());
            case $table_screen instanceof TableScreen\User :
                return new TableScreen\TableRows\User($table_screen->list_table());
            default:
                return null;
        }
    }

}