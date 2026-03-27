<?php

declare(strict_types=1);

namespace AC\Admin\MenuGroupFactory;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\TableScreen;
use AC\ThirdParty\MediaLibraryAssistant\TableScreen as MLATableScreen;

class MediaLibraryAssistantGroups implements MenuGroupFactory
{

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if ($table_screen instanceof MLATableScreen) {
            return new MenuGroup('mla', __('Media Library Assistant'), 20);
        }

        return null;
    }

}
