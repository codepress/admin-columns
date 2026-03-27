<?php

namespace AC\Integration;

use AC;
use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\Screen;
use AC\TableScreen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class MediaLibraryAssistant extends Integration implements MenuGroupFactory
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-media-library-assistant',
            __('Media Library Assistant', 'codepress-admin-columns'),
            'assets/images/addons/mla.png',
            __('Manage media metadata directly from the list table.', 'codepress-admin-columns'),
            null,
            new Site(Site::PAGE_ADDONS),
            [
                __('Display ALT text, captions, and metadata as columns', 'codepress-admin-columns'),
                __('Filter and sort across large media libraries', 'codepress-admin-columns'),
                __('Bulk update media details from a single overview', 'codepress-admin-columns'),
            ],
            __('Best for teams managing large media libraries with detailed metadata.', 'codepress-admin-columns')
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

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if ($table_screen instanceof AC\ThirdParty\MediaLibraryAssistant\TableScreen) {
            return new MenuGroup('mla', __('Media Library Assistant'), 20);
        }

        return null;
    }

}