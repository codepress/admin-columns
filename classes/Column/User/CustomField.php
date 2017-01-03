<?php

/**
 * @since NEWVERSION
 */
class AC_Column_User_Meta extends AC_Column_Meta {

	/**
	 * @return array
	 */
	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} ORDER BY 1", ARRAY_N );
	}

}