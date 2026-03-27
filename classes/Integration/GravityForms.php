<?php

declare(strict_types=1);

namespace AC\Integration;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\Screen;
use AC\TableScreen;
use AC\Type\Integration;
use AC\Type\Url\Site;
use ACA;

final class GravityForms extends Integration implements MenuGroupFactory
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-gravityforms',
            'Gravity Forms',
            'assets/images/addons/gravityforms.svg',
            __(
                'Turn your Gravity Forms entries into a sortable, filterable table. Review submissions, update entry fields inline, and export filtered results to CSV for reporting.',
                'codepress-admin-columns'
            ),
            null,
            new Site(Site::PAGE_ADDON_GRAVITYFORMS)
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('GFCommon', false);
    }

    public function show_notice(Screen $screen): bool
    {
        return 'toplevel_page_gf_edit_forms' === $screen->get_id();
    }

    public function show_placeholder(TableScreen $table_screen): bool
    {
        return $table_screen instanceof ACA\GravityForms\TableScreen\Entry;
    }

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if ($table_screen instanceof ACA\GravityForms\TableScreen\Entry) {
            return new MenuGroup(
                'gravity_forms',
                sprintf('%s - %s', __('Gravity Forms'), __('Entries', 'codepress-admin-columns')),
                20
            );
        }

        return null;
    }

}