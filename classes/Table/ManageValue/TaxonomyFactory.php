<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Column;
use AC\PostType;
use AC\Registerable;
use AC\Table\ManageValueFactory;
use AC\TableScreen;
use LogicException;

class TaxonomyFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof Taxono;
    }

    public function create(TableScreen $table_screen, Column $column): Registerable
    {
        if ( ! $table_screen instanceof PostType) {
            throw new LogicException('Invalid table screen.');
        }

        return new Post($table_screen->get_post_type(), $column);
    }

}