<?php

declare(strict_types=1);

namespace AC\Admin\MenuGroupFactory;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\PostType;
use AC\TableScreen;
use AC\Taxonomy;

class BeaverBuilderGroups implements MenuGroupFactory
{

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if (
            $table_screen instanceof PostType &&
            'fl-builder-template' === (string)$table_screen->get_post_type()
        ) {
            return new MenuGroup(
                'beaver-builder',
                __('Beaver Builder', 'codepress-admin-columns'),
                14,
                'dashicons-layout'
            );
        }

        if (
            $table_screen instanceof Taxonomy &&
            'fl-builder-template-category' === (string)$table_screen->get_taxonomy()
        ) {
            return new MenuGroup(
                'beaver-builder',
                __('Beaver Builder', 'codepress-admin-columns'),
                14,
                'dashicons-layout'
            );
        }

        return null;
    }

}
