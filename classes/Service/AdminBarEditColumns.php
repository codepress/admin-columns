<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Capabilities;
use AC\ListScreen;
use AC\Registerable;
use AC\TableScreen;
use AC\Type\Url\EditorColumns;

class AdminBarEditColumns implements Registerable
{

    public function register(): void
    {
        add_action('ac/table/screen', [$this, 'init'], 10, 2);
    }

    public function init(TableScreen $table, ?ListScreen $listscreen = null): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $admin_bar = new AdminBar(
            new EditorColumns(
                $table->get_id(),
                $listscreen
                    ? $listscreen->get_id()
                    : null
            ),
            __('Edit Columns', 'codepress-admin-columns'),
            'ac-edit-columns'
        );
        $admin_bar->register();
    }

}