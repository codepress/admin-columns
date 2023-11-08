<?php

namespace AC;

class ListScreenGroupsFactory
{

    public static function create(): Groups
    {
        $groups = new Groups();

        $groups->add('post', __('Post Type', 'codepress-admin-columns'), 5, 'cpacicon-gf-article');
        $groups->add('user', __('Users'), 10, 'dashicons-admin-users');
        $groups->add('media', __('Media'), 10, 'dashicons-admin-media');
        $groups->add('comment', __('Comments'), 20, 'cpacicon-gf-comment');
        $groups->add('link', __('Links'), 40, 'dashicons-');
        $groups->add('other', __('Other'), 50);

        do_action('ac/list_screen_groups', $groups);

        return $groups;
    }

}