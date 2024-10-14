<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant\TableScreen;

use AC;
use AC\Table\SaveHeading\ScreenColumnsFactory;
use AC\TableScreen;

class SaveHeadings extends ScreenColumnsFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof AC\ThirdParty\MediaLibraryAssistant\TableScreen;
    }

}