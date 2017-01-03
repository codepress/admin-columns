<?php

class AC_Helper_Meta {

	private function get_query( $meta_type, $table, $id_column, $key, array $in ) {
		global $wpdb;

		// setup meta tables
		$q = new WP_Meta_Query();
		$q->get_sql( $meta_type, $table, $id_column );

		// escape integers before imploding
		array_walk( $in, 'intval' );

		$in = implode( ', ', $in );

		$sql = "
			SELECT t.$id_column, m.meta_value
			FROM $table AS t
			LEFT JOIN $q->meta_table AS m ON m.$q->meta_id_column = t.$id_column 
				AND m.meta_key = %s
			WHERE t.$id_column IN ( $in )
			ORDER BY m.meta_value
		";

		return $wpdb->prepare( $sql, $key );
	}

	/**
	 * @return array
	 */
	public function get_values_by_ids( $ids, $meta_key, $meta_type = 'post' ) {
		global $wpdb;

		switch ( $meta_type ) {
			case 'user':
				$table = $wpdb->users;
				$id_column = 'ID';

				break;
			case 'comment':
				$table = $wpdb->comments;
				$id_column = 'comment_ID';

				break;
			default:
				$table = $wpdb->posts;
				$id_column = 'ID';
		}

		$sql = $this->get_query( $meta_type, $table, $id_column, $meta_key, $ids );
		$r = $wpdb->get_results( $sql, ARRAY_A );

		$values = array();

		if ( is_array( $r ) ) {
			foreach ( $r as $row ) {
				$values[ $row['ID'] ] = maybe_unserialize( $row['meta_value'] );
			}
		}

		return $values;
	}

}