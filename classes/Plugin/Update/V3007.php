<?php

namespace AC\Plugin\Update;

use AC\Plugin\Update;
use AC\Plugin\Version;

class V3007 extends Update {

	public function __construct() {
		parent::__construct( new Version( '3.0.7' ) );
	}

	public function apply_update() {
		$this->update_roles_column();
	}

	/**
	 * Change the roles columns to the author column
	 */
	private function update_roles_column() {
		global $wpdb;

		$sql = "
			SELECT *
			FROM {$wpdb->options}
			WHERE option_name LIKE 'cpac_options_%'
		";

		$results = $wpdb->get_results( $sql );

		if ( ! is_array( $results ) ) {
			return;
		}

		foreach ( $results as $row ) {
			$options = maybe_unserialize( $row->option_value );
			$update = false;

			if ( ! is_array( $options ) ) {
				continue;
			}

			foreach ( $options as $k => $v ) {
				if ( ! is_array( $v ) || empty( $v['type'] ) || $v['type'] !== 'column-roles' ) {
					continue;
				}

				$v['type'] = 'column-author_name';
				$v['display_author_as'] = 'roles';
				$v['edit'] = 'off';

				$options[ $k ] = $v;

				$update = true;
			}

			if ( $update ) {
				update_option( $row->option_name, $options, false );
			}
		}
	}

}