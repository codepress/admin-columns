<?php

declare(strict_types=1);

namespace AC\Plugin\Install;

use AC;

final class Database implements AC\Plugin\Install
{

    private AC\Storage\Table\AdminColumns $table;

    public function __construct(AC\Storage\Table\AdminColumns $table)
    {
        $this->table = $table;
    }

    public function install(): void
    {
        $this->create_database();
    }

    private function create_database(): void
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta($this->table->get_schema());
    }

}