<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Capabilities;
use AC\ListScreen;
use AC\Registerable;

class AdminBarEditColumns implements Registerable
{

    public function register(): void
    {
        add_action('ac/table/list_screen', [$this, 'init']);
    }

    public function init(ListScreen $listscreen): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $admin_bar = new AdminBar(
            $listscreen->get_editor_url(),
            __('Edit Columns', 'codepress-admin-columns'),
            'ac-edit-columns'
        );
        $admin_bar->register();
    }

}