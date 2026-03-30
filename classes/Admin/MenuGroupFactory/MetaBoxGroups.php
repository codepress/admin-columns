<?php

declare(strict_types=1);

namespace AC\Admin\MenuGroupFactory;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\PostType;
use AC\TableScreen;

class MetaBoxGroups implements MenuGroupFactory
{

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if (
            $table_screen instanceof PostType &&
            in_array(
                (string)$table_screen->get_post_type(),
                ['meta-box', 'mb-taxonomy', 'mb-relationship', 'mb-post-type'],
                true
            )
        ) {
            return new MenuGroup('metabox', 'MetaBox', 14);
        }

        return null;
    }

}
