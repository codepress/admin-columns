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

                if ( ! $post_type) {
                    return null;
                }

                if ('post' === $post_type->name) {
                    return $this->get_default_group(1);
                }

                if ('page' === $post_type->name) {
                    return $this->get_default_group(2);
                }

                if ($post_type->show_in_menu) {
                    return new MenuGroup('post', __('Post Types', 'codepress-admin-columns'), 7, 'cpacicon-gf-article');
                }

                return new MenuGroup(
                    'post-hidden',
                    sprintf(
                        '%s (%s)',
                        __('Custom Post Types', 'codepress-admin-columns'),
                        __('hidden', 'codepress-admin-columns')
                    ),
                    30
                );
            case $table_screen instanceof TableScreen\User :
                return $this->get_default_group(4);
            case $table_screen instanceof TableScreen\Media :
                return $this->get_default_group(5);
            case $table_screen instanceof TableScreen\Comment :
                return $this->get_default_group(6);
            default :
                return null;
        }
    }

    private function get_default_group(int $prio): MenuGroup
    {
        return new MenuGroup('default', __('Default'), $prio, 'dashicons-wordpress');
    }

}