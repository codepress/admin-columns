<?php

namespace AC\Plugin\Update;

use AC\Plugin\Update;
use AC\Plugin\Version;

class V3005 extends Update {

	public function __construct() {
		parent::__construct( new Version( '3.0.5' ) );
	}

	public function apply_update(): void
    {
		$this->migrate_user_specific_settings();
		$this->delete_deprecated_settings();
		$this->delete_deprecated_options();
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	private function validate_key( $key ) {
		if ( empty( $key ) ) {
			return false;
		}

		if ( ! is_string( $key ) ) {
			return false;
		}

		if ( strlen( $key ) < 10 ) {
			return false;
		}

		return true;
	}

	/**
	 * @param string $key
	 *
	 * @return array
	 */
	private function get_meta( $key ) {
		global $wpdb;

		if ( ! $this->validate_key( $key ) ) {
			return [];
		}

		$sql = $wpdb->prepare( "
			SELECT *
			FROM {$wpdb->usermeta}
			WHERE meta_key LIKE %s
			ORDER BY user_id ASC
		", $key );

		$results = $wpdb->get_results( $sql );

		if ( ! $results ) {
			return [];
		}

		return $results;
	}

	/**
	 * Migrate USER specific preferences
	 */
	private function migrate_user_specific_settings() {
		global $wpdb;

		$mapping = [
			'cpac-hide-install-addons-notice' => 'ac_hide_notice_addons',
			'cpac-hide-review-notice'         => 'ac_hide_notice_review',
		];

		foreach ( $mapping as $current => $new ) {
			$sql_meta_key = $wpdb->esc_like( $current ) . '%';

			foreach ( $this->get_meta( $sql_meta_key ) as $row ) {
				update_user_meta( $row->user_id, $new, $row->meta_value );
			}

			$this->delete( $current );
		}
	}

	/**
	 * Preference to be REMOVED
	 */
	private function delete_deprecated_settings() {
		$this->delete( 'cpac_current_model' );
		$this->delete( 'cpac-install-timestamp' );
	}

	private function delete_deprecated_options() {
		delete_option( 'cpac_version' );
		delete_option( 'cpac_version_prev' );
		delete_option( 'cpac-install-timestamp' );
	}

	/**
	 * Remove meta data
	 *
	 * @param string $key
	 */
	private function delete( $key ) {
		global $wpdb;

		if ( ! $this->validate_key( $key ) ) {
			return;
		}

		$sql = $wpdb->prepare( "
				DELETE
				FROM {$wpdb->usermeta}
				WHERE meta_key LIKE %s
			", $wpdb->esc_like( $key ) . '%' );

		$wpdb->query( $sql );
	}

}