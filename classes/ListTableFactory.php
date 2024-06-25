<?php

namespace AC;

use AC\ListTable\Comment;
use AC\ListTable\Media;
use AC\ListTable\NetworkSite;
use AC\ListTable\NetworkUser;
use AC\ListTable\Post;
use AC\ListTable\Taxonomy;
use AC\ListTable\User;
use WP_Comments_List_Table;
use WP_Media_List_Table;
use WP_MS_Sites_List_Table;
use WP_MS_Users_List_Table;
use WP_Posts_List_Table;
use WP_Terms_List_Table;
use WP_Users_List_Table;

class ListTableFactory
{

    public static function create_from_globals(): ?ListTable
    {
        global $wp_list_table, $current_screen;

        switch (true) {
            case $wp_list_table instanceof WP_Posts_List_Table :
                return new Post($wp_list_table);

            case $wp_list_table instanceof WP_Users_List_Table :
                return new User($wp_list_table);

            case $wp_list_table instanceof WP_Comments_List_Table :
                return new Comment($wp_list_table);

            case $wp_list_table instanceof WP_Media_List_Table :
                return new Media($wp_list_table);

            case $wp_list_table instanceof WP_Terms_List_Table :
                if ( ! $current_screen) {
                    return null;
                }

                return new Taxonomy($wp_list_table, $current_screen->taxonomy);

            case $wp_list_table instanceof WP_MS_Users_List_Table :
                return new NetworkUser($wp_list_table);

            case $wp_list_table instanceof WP_MS_Sites_List_Table :
                return new NetworkSite($wp_list_table);
        }

        return null;
    }

    public static function create_post(string $screen_id): Post
    {
        return new Post(WpListTableFactory::create_post_table($screen_id));
    }

    public static function create_user(string $screen_id): User
    {
        return new User(WpListTableFactory::create_user_table($screen_id));
    }

    public static function create_network_user(string $screen_id): NetworkUser
    {
        return new NetworkUser(WpListTableFactory::create_network_user_table($screen_id));
    }

    public static function create_comment(string $screen_id): Comment
    {
        return new Comment(WpListTableFactory::create_comment_table($screen_id));
    }

    public static function create_media(string $screen_id): Media
    {
        return new Media(WpListTableFactory::create_media_table($screen_id));
    }

    public static function create_taxonomy(string $screen_id, string $taxonomy): Taxonomy
    {
        return new Taxonomy(WpListTableFactory::create_taxonomy_table($screen_id), $taxonomy);
    }

}