<?php

namespace AC\Integration;

use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class MetaBox extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-metabox',
            __('Meta Box', 'codepress-admin-columns'),
            'assets/images/addons/metabox.svg',
            __(
                'Display Meta Box fields as sortable, editable columns. Filter by custom fields, bulk update values, and manage your Meta Box content from a single overview.',
                'codepress-admin-columns'
            ),
            null,
            new Site(Site::PAGE_ADDON_METABOX)
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('RWMB_Loader', false);
    }

    public function show_notice(Screen $screen): bool
    {
        return $screen->get_id() === 'edit-meta-box';
    }

}