<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Registerable;
use AC\Table\ManageValueFactory;
use AC\Table\Renderable;
use AC\TableScreen;
use AC\Type\ColumnId;
use LogicException;

class MediaFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Media;
    }

    public function create(ColumnId $column_id, Renderable $renderable, TableScreen $table_screen): Registerable
    {
        if ( ! $table_screen instanceof TableScreen\Media) {
            throw new LogicException('Invalid table screen.');
        }

        return new Media($column_id, $renderable);
    }

}