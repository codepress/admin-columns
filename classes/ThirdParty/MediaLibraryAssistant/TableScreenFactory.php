<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Type\ListKey;
use MLACore;
use WP_Screen;

class TableScreenFactory implements AC\TableScreenFactory
{

    protected function create_table_screen(): TableScreen
    {
        return new TableScreen();
    }

    public function create(ListKey $key): AC\TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create(ListKey $key): bool
    {
        return $key->equals(new ListKey('mla-media-assistant'));
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