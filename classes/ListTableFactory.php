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

}