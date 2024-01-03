<?php

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Registerable;

class MediaLibraryAssistant implements Registerable
{

    public function register(): void
    {
        if ( ! defined('MLA_PLUGIN_PATH')) {
            return;
        }

        AC\TableScreenFactory\Aggregate::add(new TableScreenFactory());
        AC\ListKeysFactory\Aggregate::add(new ListKeysFactory());
        AC\ColumnTypesFactory\Aggregate::add(new ColumnTypesFactory());
    }

}