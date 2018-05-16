<?php

namespace AC\Plugin\Update;

use AC\Plugin\Update;
use AC\Preferences;

class V3105 extends Update {

	public function apply_update() {
		$this->update_user_preferences();
	}

	protected function set_version() {
		$this->version = '3.1.5';
	}

	/**
	 * Change the roles columns to the author column
	 */
	private function update_user_preferences() {

		$mapping = array(
			'ac_hide_notice_addons' => 'addon-nudge',
			'ac_hide_notice_review' => 'dismiss-review',
		);

		$user_ids = get_users( array( 'fields' => 'ids' ) );

		foreach ( $user_ids as $user_id ) {

			foreach ( $mapping as $old => $new ) {
				$value = get_user_meta( $user_id, $old, true );

				$notices = new Preferences\User( 'notices' );
				$notices->set( 'dismiss-' . $new, (bool) $value );

				delete_user_meta( $user_id, $old );
			}
		}
	}

}