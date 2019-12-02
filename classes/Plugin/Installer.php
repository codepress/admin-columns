<?php
namespace AC\Plugin;

class Installer {

	const TABLE = 'admin_columns';

	public function install() {
		$this->create_data_base();
	}

	private function create_data_base() {
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
			title text NOT NULL,
			list_id varchar(20) NOT NULL default '',
			list_key varchar(100) NOT NULL default '',
			date_modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			columns longtext,
			settings longtext,
			PRIMARY KEY (id)
		) $collate;
		";

		return $table;
	}

}