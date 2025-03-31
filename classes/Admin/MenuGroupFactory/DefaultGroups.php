<?php

declare(strict_types=1);

namespace AC\Admin\MenuGroupFactory;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\TableScreen;

class DefaultGroups implements MenuGroupFactory
{

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        switch ($table_screen) {
            case $table_screen instanceof TableScreen\Post :
                $post_type = get_post_type_object((string)$table_screen->get_post_type());

                if ($post_type && in_array($post_type->name, ['post', 'page'], true)) {
                    return new MenuGroup('default', __('Default'), 1, 'dashicons-wordpress');
                }

                if ($post_type && $post_type->show_in_menu) {
                    return new MenuGroup('post', __('Post Types'), 5, 'cpacicon-gf-article');
                }

                return new MenuGroup(
                    'post-hidden',
                    sprintf('%s (%s)', __('Custom Post Types'), __('hidden', 'codepress-admin-columns')),
                    30
                );
            case $table_screen instanceof TableScreen\User :
            case $table_screen instanceof TableScreen\Media :
            case $table_screen instanceof TableScreen\Comment :
                return new MenuGroup('default', __('Default'), 1, 'dashicons-wordpress');
            default :
                return null;
        }
    }

    private function get_default_group(): MenuGroup
    {
        return new MenuGroup('default', __('Default'), 1, 'dashicons-wordpress');
    }

}