<?php

namespace AC\Integration;

use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class BeaverBuilder extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-beaver-builder',
            __('Beaver Builder', 'codepress-admin-columns'),
            'assets/images/addons/beaver-builder.png',
            __(
                'Manage Beaver Builder templates, saved rows, and modules from a sortable, filterable overview.',
                'codepress-admin-columns'
            ),
            null,
            new Site(Site::PAGE_ADDONS)
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('FLBuilderLoader', false);
    }

    public function show_notice(Screen $screen): bool
    {
        return 'edit' === $screen->get_base()
               && 'fl-builder-template' === $screen->get_post_type();
    }

}
