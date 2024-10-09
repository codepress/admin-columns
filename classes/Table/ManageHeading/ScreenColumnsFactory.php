<?php

declare(strict_types=1);

namespace AC\Table\ManageHeading;

use AC\ListScreen;
use AC\Registerable;
use AC\TableScreen;
use AC\TableScreen\ManageHeadingFactory;

abstract class ScreenColumnsFactory implements ManageHeadingFactory
{

    use EncodeColumnsTrait;

    public function create(TableScreen $table_screen, ListScreen $list_screen): ?Registerable
    {
        $headings = $this->encode_columns($list_screen);

        if ( ! $headings) {
            return null;
        }

        return new TableScreen\ManageHeading\ScreenColumns(
            $table_screen->get_screen_id(),
            $headings
        );
    }

}