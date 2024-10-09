<?php

declare(strict_types=1);

namespace AC\Table\ManageHeading;

use AC\ListScreen;
use AC\Registerable;
use AC\Table\ManageHeadingFactory;
use AC\TableScreen;

class WpListTableFactory implements ManageHeadingFactory
{

    use EncodeColumnsTrait;

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Post ||
               $table_screen instanceof TableScreen\User ||
               $table_screen instanceof TableScreen\Comment ||
               $table_screen instanceof TableScreen\Media;
    }

    public function create(TableScreen $table_screen, ListScreen $list_screen): ?Registerable
    {
        $headings = $this->encode_columns($list_screen);

        if ( ! $headings) {
            return null;
        }

        return new TableScreen\ManageHeading\WpListTable(
            $table_screen->get_screen_id(),
            $headings

        // TODO wrap priority in dev filer?
        );
    }

}