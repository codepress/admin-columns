<?php

namespace AC\Integration;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\PostType;
use AC\Screen;
use AC\TableScreen;
use AC\Type\Integration;
use AC\Type\Url\External;
use AC\Type\Url\Site;

final class JetEngine extends Integration implements MenuGroupFactory
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-jetengine',
            'JetEngine',
            'assets/images/addons/jetengine.svg?v3',
            __('Manage JetEngine data directly from the list table.', 'codepress-admin-columns'),
            new External('https://crocoblock.com/plugins/jetengine/'),
            new Site(Site::PAGE_ADDON_JETENGINE),
            [
                __('Display custom fields and relations as columns', 'codepress-admin-columns'),
                __('Sort and filter by any JetEngine field', 'codepress-admin-columns'),
                __('Edit field values without opening each item', 'codepress-admin-columns'),
            ],
            __('Best for developers building dynamic sites with custom content types.', 'codepress-admin-columns')
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('Jet_Engine', false);
    }

    public function show_notice(Screen $screen): bool
    {
        return in_array($screen->get_id(), [
            'toplevel_page_jet-engine',
            'jetengine_page_jet-engine-meta',
            'jetengine_page_jet-engine-cpt',
            'jetengine_page_jet-engine-cpt-tax',
            'jetengine_page_jet-engine-relations',
        ]);
    }

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if (
            $table_screen instanceof PostType &&
            $table_screen->get_post_type()->equals('jet-engine')
        ) {
            return new MenuGroup('jet-engine', 'JetEngine', 14);
        }

        return null;
    }

}