<?php

namespace AC\Plugin\Install;

use AC;

class Database implements AC\Plugin\Install {

	const TABLE = 'admin_columns';

	public function install() {
		$this->create_database();
	}

	private function create_database() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $this->get_schema() );
	}

	private function get_schema() {
		global $wpdb;

		$collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . self::TABLE;

		$table = "
		CREATE TABLE {$table_name} (
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