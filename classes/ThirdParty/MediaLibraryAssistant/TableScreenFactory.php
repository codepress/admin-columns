<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Type\TableId;
use MLACore;
use WP_Screen;

class TableScreenFactory implements AC\TableScreenFactory
{

    protected function create_table_screen(): TableScreen
    {
        return new TableScreen();
    }

    public function create(TableId $id): AC\TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create(TableId $id): bool
    {
        return $id->equals(new TableId('mla-media-assistant'));
    }

    public function create_from_wp_screen(WP_Screen $screen): AC\TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return class_exists('MLACore') && 'media_page_' . MLACore::ADMIN_PAGE_SLUG === $screen->id;
    }

}