<?php

namespace AC\Admin\MenuPageFactory;

use AC\Admin\Admin;
use AC\Admin\MenuPageFactory;
use AC\Capabilities;

class Menu implements MenuPageFactory
{

    public function create(array $args = []): string
    {
        return add_menu_page(
            __('Admin Columns Settings', 'codepress-admin-columns'),
            __('Admin Columns', 'codepress-admin-columns'),
            Capabilities::MANAGE,
            Admin::NAME,
            null,
            $args['icon'] ?? null,
            $args['position'] ?? null
        );
    }

}