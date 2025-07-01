<?php

declare(strict_types=1);

namespace AC\Table\ManageHeading;

use AC\Registerable;
use AC\TableScreen;
use AC\TableScreen\ManageHeadingFactory;

abstract class ScreenColumnsFactory implements ManageHeadingFactory
{

    public function create(TableScreen $table_screen, array $headings): Registerable
    {
        return new TableScreen\ManageHeading\ScreenColumns(
            $table_screen->get_screen_id(),
            $headings
        );
    }

}