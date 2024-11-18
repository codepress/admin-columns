<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant\TableScreen;

use AC;
use AC\Registerable;
use AC\ThirdParty\MediaLibraryAssistant\TableScreen;

class ManageValueFactory implements AC\TableScreen\ManageValueFactory
{

    public function can_create(AC\TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen;
    }

    public function create(AC\TableScreen $table_screen, AC\CellRenderer $renderable, int $priority = 100): Registerable
    {
        return new ManageValue($renderable, $priority);
    }

}