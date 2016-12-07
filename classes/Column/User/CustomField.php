<?php

/**
 * @since NEWVERSION
 */
class AC_Column_User_CustomField extends AC_Column_CustomField {

	/**
	 * @return array
	 */
	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} ORDER BY 1", ARRAY_N );
	}

}