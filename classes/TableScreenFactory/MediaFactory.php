<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\TableScreen;
use AC\TableScreen\Media;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use WP_Screen;

class MediaFactory implements TableScreenFactory
{

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'upload' === $screen->base && 'upload' === $screen->id && 'attachment' === $screen->post_type;
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create(ListKey $key): bool
    {
        return $key->equals(new ListKey('wp-media'));
    }

    public function create(ListKey $key): TableScreen
    {
        return $this->create_table_screen();
    }

    protected function create_table_screen(): Media
    {
        return new Media();
    }

}