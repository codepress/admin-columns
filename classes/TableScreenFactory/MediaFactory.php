<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\TableScreen;
use AC\TableScreen\Media;
use AC\TableScreenFactory;
use AC\Type\TableId;
use WP_Screen;

class MediaFactory implements TableScreenFactory
{

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'upload' === $screen->base &&
               'upload' === $screen->id &&
               'attachment' === $screen->post_type &&
               'list' === ($_GET['mode'] ?? get_user_option('media_library_mode'));
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create(TableId $id): bool
    {
        return $id->equals(new TableId('wp-media'));
    }

    public function create(TableId $id): TableScreen
    {
        return $this->create_table_screen();
    }

    protected function create_table_screen(): Media
    {
        return new Media();
    }

}