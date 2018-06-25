<?php

namespace AC\Plugin\Update;

use AC\Plugin\Update;
use AC\Preferences;

class V3200 extends Update {

	public function get_dir() {
		return AC()->get_dir() . '/classes';
	}

	public function apply_update() {
		$this->uppercase_class_files();
		$this->update_notice_preference_review();
		$this->update_notice_preference_addons();
	}

	protected function set_version() {
		$this->version = '3.2.0';
	}

	/**
	 * Set all files to the proper case
	 */
	private function uppercase_class_files() {
		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator( $this->get_dir(), \FilesystemIterator::SKIP_DOTS )
		);

		foreach ( $iterator as $leaf ) {
			$file = $leaf->getFilename();

			if ( $file == strtolower( $file ) ) {
				@rename( $leaf->getPathname(), trailingslashit( $leaf->getPath() ) . ucfirst( $file ) );
			}
		}
	}

	private function update_notice_preference_review() {
		$mapping = array(
			'ac_hide_notice_review'    => 'dismiss-review',
			'ac-first-login-timestamp' => 'first-login-review',
		);

		foreach ( $this->get_admins() as $user_id ) {

			foreach ( $mapping as $old => $new ) {
				$value = get_user_meta( $user_id, $old, true );

				$option = new Preferences\User( 'check-review' );
				$option->set( $new, $value );

				delete_user_meta( $user_id, $old );
			}
		}
	}

	private function update_notice_preference_addons() {
		$mapping = array(
			'ac_hide_notice_addons' => 'dismiss-notice',
		);

		foreach ( $this->get_admins() as $user_id ) {

			foreach ( $mapping as $old => $new ) {
				$value = get_user_meta( $user_id, $old, true );

				$option = new Preferences\User( 'check-addon-available' );
				$option->set( $new, $value );

				delete_user_meta( $user_id, $old );
			}
		}
	}

	/**
	 * @return array
	 */
	private function get_admins() {
		$users = get_users( array(
			'fields'      => 'ids',
			'role'        => 'administrator',
			'count_total' => false,
		) );

		if ( ! $users ) {
			return array();
		}

		return $users;
	}

}