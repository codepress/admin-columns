<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant\ManageHeadings;

use AC\Table\ManageHeading\EncodeColumnsTrait;
use AC\Table\ManageHeading\WpListTableFactory;
use AC\TableScreen;
use AC\ThirdParty\MediaLibraryAssistant;

class MediaFactory extends WpListTableFactory
{

    use EncodeColumnsTrait;

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof MediaLibraryAssistant\TableScreen;
    }

}