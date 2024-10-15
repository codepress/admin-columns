<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant\TableScreen;

use AC\Table\ManageHeading\EncodeColumnsTrait;
use AC\Table\ManageHeading\ScreenColumnsFactory;
use AC\TableScreen;
use AC\ThirdParty\MediaLibraryAssistant;

class ManageHeadings extends ScreenColumnsFactory
{

    use EncodeColumnsTrait;

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof MediaLibraryAssistant\TableScreen;
    }

}