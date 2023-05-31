<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Media;
use WP_Screen;

class MediaFactory extends BaseFactory
{

    protected function create_list_screen(string $key): ListScreen
    {
        return new Media();
    }

    protected function create_list_screen_from_wp_screen(WP_Screen $screen): ListScreen
    {
        return new Media();
    }

    public function can_create(string $key): bool
    {
        return 'wp-media' === $key;
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'upload' === $screen->base && 'upload' === $screen->id && 'attachment' === $screen->post_type;
    }

}