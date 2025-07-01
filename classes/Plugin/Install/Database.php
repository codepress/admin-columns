<?php

namespace AC\Plugin\Install;

use AC;

final class Database implements AC\Plugin\Install
{

    private AC\Storage\Table\AdminColumns $admin_columns;

    public function __construct( AC\Storage\Table\AdminColumns $admin_columns )
    {
        $this->admin_columns = $admin_columns;
    }

    public function install() : void
    {
        $this->create_database();
    }

    private function create_database(): void
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta( $this->admin_columns->get_schema() );
    }

    public function verify_database_exists(): bool
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        global $wpdb;

        $suppress_errors = $wpdb->suppress_errors();
        $created = dbDelta( $this->admin_columns->get_schema(), false );
        $wpdb->suppress_errors($suppress_errors);

        return 1 !== count($created);
    }

}