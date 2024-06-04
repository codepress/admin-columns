<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;
use AC\Type\Url\Site;
use ACA;

final class GravityForms extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-gravityforms',
            'Gravity Forms',
            'assets/images/addons/gravityforms.svg',
            sprintf(
                '%s %s',
                sprintf(
                    __('Integrates %s with Admin Columns.', 'codepress-admin-columns'),
                    __('Gravity Forms', 'codepress-admin-columns')
                ),
                sprintf(
                    __(
                        'Display, inline- and bulk-edit, export, smart filter and sort your %s Entries.',
                        'codepress-admin-columns'
                    ),
                    __('Gravity Forms', 'codepress-admin-columns')
                )
            ),
            new Site(Site::PAGE_ADDON_GRAVITYFORMS)
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('GFCommon', false);
    }

    public function show_notice(Screen $screen): bool
    {
        return 'forms_page_gf_entries' === $screen->get_id();
    }

}