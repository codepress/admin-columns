<?php

declare(strict_types=1);

namespace AC\Table\ManageHeading;

use AC\TableScreen;

class WpListTableFactory extends ScreenColumnsFactory
{

    use EncodeColumnsTrait;

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Post ||
               $table_screen instanceof TableScreen\User ||
               $table_screen instanceof TableScreen\Comment ||
               $table_screen instanceof TableScreen\Media;
    }

}