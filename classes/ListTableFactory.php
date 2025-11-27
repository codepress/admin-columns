<?php

namespace AC;

use AC\ListTable\Comment;
use AC\ListTable\Media;
use AC\ListTable\NetworkSite;
use AC\ListTable\NetworkUser;
use AC\ListTable\Post;
use AC\ListTable\Taxonomy;
use AC\ListTable\User;

class ListTableFactory
{

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

    public static function create_network_site(string $screen_id): NetworkSite
    {
        return new NetworkSite(WpListTableFactory::create_network_site_table($screen_id));
    }

}