<?php

namespace AC\Plugin\Install;

use AC;

final class Database implements AC\Plugin\Install
{

    public function install()
    {
        $this->create_database();
    }

    private function create_database(): void
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta(self::get_schema());
    }

    public static function verify_database_exists(): bool
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        global $wpdb;

        $suppress_errors = $wpdb->suppress_errors();
        $created = dbDelta(self::get_schema(), false);
        $wpdb->suppress_errors($suppress_errors);

        return 1 !== count($created);
    }

    private static function get_schema(): string
    {
        global $wpdb;

        $collate = $wpdb->has_cap('collation')
            ? $wpdb->get_charset_collate()
            : '';

        $table = "
		CREATE TABLE {$wpdb->prefix}admin_columns (
			id bigint(20) unsigned NOT NULL auto_increment,
			list_id varchar(20) NOT NULL default '',
			list_key varchar(100) NOT NULL default '',
			title varchar(255) NOT NULL default '',
			columns mediumtext,
			settings mediumtext,
			date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			date_modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY `list_id` (`list_id`)
		) $collate;
		";

        return $table;
    }

}