<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Comment_CustomField extends AC_Column_CustomField {

	/**
	 * @return array
	 */
	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1", ARRAY_N );
	}

}