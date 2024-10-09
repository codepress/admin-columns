<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Registerable;
use AC\TableScreen\ManageValue\GridRenderable;

class ManageValueFactory implements AC\TableScreen\ManageValueFactory
{

    public function can_create(AC\TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen;
    }

    public function create(AC\TableScreen $table_screen, GridRenderable $renderable, int $priority = 100): Registerable
    {
        return new ManageValue($renderable, $priority);
    }

}