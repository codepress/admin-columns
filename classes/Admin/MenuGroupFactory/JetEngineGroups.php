<?php

declare(strict_types=1);

namespace AC\Admin\MenuGroupFactory;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\PostType;
use AC\TableScreen;

class JetEngineGroups implements MenuGroupFactory
{

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if (
            $table_screen instanceof PostType &&
            $table_screen->get_post_type()->equals('jet-engine')
        ) {
            return new MenuGroup('jet-engine', 'JetEngine', 14);
        }

        return null;
    }

}
