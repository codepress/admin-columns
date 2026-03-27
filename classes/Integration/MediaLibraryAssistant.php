<?php

declare(strict_types=1);

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
            __(
                'Take control of large media libraries. Add ALT text, captions, and custom metadata as columns - then filter, sort, and bulk update across thousands of files.',
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

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if ($table_screen instanceof AC\ThirdParty\MediaLibraryAssistant\TableScreen) {
            return new MenuGroup('mla', __('Media Library Assistant'), 20);
        }

        return null;
    }

}