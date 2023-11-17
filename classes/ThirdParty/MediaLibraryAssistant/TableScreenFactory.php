<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Type\ListKey;
use MLACore;
use WP_Screen;

class TableScreenFactory implements AC\TableScreenFactory
{

    public function create(ListKey $key): AC\TableScreen
    {
        return new TableScreen();
    }

    public function can_create(ListKey $key): bool
    {
        return $key->equals(new ListKey('mla-media-assistant'));
    }

    public function create_from_wp_screen(WP_Screen $screen): AC\TableScreen
    {
        return new TableScreen();
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return class_exists('MLACore') && 'media_page_' . MLACore::ADMIN_PAGE_SLUG === $screen->id;
    }

}