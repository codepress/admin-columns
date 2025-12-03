<?php

namespace AC\Integration;

use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class MediaLibraryAssistant extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-media-library-assistant',
            __('Media Library Assistant', 'codepress-admin-columns'),
            'assets/images/addons/mla.png',
            __(
                'The Media Library Assistant plugin from David Lingren provides several enhancements for managing the Media Library.',
                'codepress-admin-columns'
            ),
            null,
            new Site(Site::PAGE_ADDONS)
        );
    }

    public function is_plugin_active(): bool
    {
        return defined('MLA_PLUGIN_PATH');
    }

    public function show_notice(Screen $screen): bool
    {
        return false;
    }

}