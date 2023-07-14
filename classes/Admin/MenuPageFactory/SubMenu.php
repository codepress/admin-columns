<?php

namespace AC\Admin\MenuPageFactory;

use AC\Admin\Admin;
use AC\Admin\MenuPageFactory;
use AC\Capabilities;

class SubMenu implements MenuPageFactory
{

    public function create(array $args = []): string
    {
        return add_submenu_page(
            $args['parent'],
            __('Admin Columns Settings', 'codepress-admin-columns'),
            __('Admin Columns', 'codepress-admin-columns'),
            Capabilities::MANAGE,
            Admin::NAME,
            null,
            $args['position'] ?? null
        );
    }

}