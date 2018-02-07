<?php

class AC_Plugin_Update_V3105 extends AC_Plugin_Update {

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
		global $wpdb;

		// TODO

		// See: AC_Plugin_Update_V3005::migrate_user_specific_settings
		$mapping = array(
			'ac_hide_notice_addons' => '....',
			'ac_hide_notice_review' => '....',
		);
	}

}