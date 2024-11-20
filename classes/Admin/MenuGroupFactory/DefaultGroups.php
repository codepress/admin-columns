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

                if ( $post_type && $post_type->show_in_menu) {
                    return new MenuGroup('post', __('Post Type'), 5, 'cpacicon-gf-article');
                }

                return new MenuGroup(
                    'post-hidden',
                    sprintf('%s (%s)', __('Post Type'), __('hidden', 'codepress-admin-columns')),
                    30
                );
            case $table_screen instanceof TableScreen\User :
                return new MenuGroup('user', __('Users'), 12, 'dashicons-admin-users');
            case $table_screen instanceof TableScreen\Media :
                return new MenuGroup('media', __('Media'), 13, 'dashicons-admin-media');
            case $table_screen instanceof TableScreen\Comment :
                return new MenuGroup('comment', __('Comments'), 14, 'cpacicon-gf-comment');
            default :
                return null;
        }
    }

}