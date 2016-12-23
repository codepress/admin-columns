<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Post_CustomField extends AC_Column_CustomField {

	protected function get_cache_key() {
		return $this->get_post_type();
	}

	/**
	 * @return array
	 */
	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $this->get_post_type() ), ARRAY_N );
	}

	/**
	 * @return array
	 */
	public function get_meta_values( $ids ) {
		global $wpdb;

		// escape integers before imploding
		array_walk( $ids, 'intval' );

		$in = implode( ', ', $ids );

		$sql = "
			SELECT p.ID, pm.meta_value
			FROM {$wpdb->posts} p
			LEFT JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID
			WHERE p.post_type = %s
			AND pm.meta_key = %s
			AND p.ID IN ( $in )
			ORDER BY pm.meta_value
		";

		$sql = $wpdb->prepare( $sql, $this->get_post_type(), $this->get_field_key() );
		$r = $wpdb->get_results( $sql, ARRAY_A );

		$values = array();

		if ( is_array( $r ) ) {
			foreach ( $r as $row ) {
				$values[ $row['ID'] ] = $this->get_single_value( $row['meta_value'] );
			}
		}

		return $values;
	}
}