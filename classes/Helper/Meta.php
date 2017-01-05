<?php

class AC_Helper_Meta {

	/**
	 * Returns the properties needed to write custom SQL for the current meta table
	 *
	 * @param string $meta_type
	 *
	 * @return false|object
	 */
	public function get_meta_table_properties( $meta_type ) {
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
			case 'post':
				$table = $wpdb->posts;
				$id_column = 'ID';

				break;
			default:
				return false;
		}

		// setup meta tables
		$query = new WP_Meta_Query();
		$query->get_sql( $meta_type, $table, $id_column );

		return (object) array(
			'table'      => $table,
			'id'         => $id_column,
			'meta_id'    => $query->meta_id_column,
			'meta_table' => $query->meta_table,
		);
	}

	/**
	 * Retrieve meta value per id
	 *
	 * @todo handle identical meta_keys per id
	 * @return array
	 */
	public function get_values_by_ids( $ids, $meta_key, array $args = array() ) {
		global $wpdb;

		$defaults = array(
			'meta_type' => 'post',
			'orderby'   => null,
			'order'     => 'ASC',
			'data_type' => null,
		);

		$args = (object) array_merge( $defaults, $args );

		// sanitize args
		if ( $args->order !== 'ASC' || $args->order !== 'DESC' ) {
			$args->order = $defaults['order'];
		}

		if ( ! in_array( $args->orderby, array( 'meta_value', 'meta_key', 'id' ) ) ) {
			$args->orderby = $defaults['orderby'];
		}

		$properties = $this->get_meta_table_properties( $args->meta_type );

		if ( ! $properties ) {
			return array();
		}

		// sanitize ids
		array_walk( $ids, 'intval' );

		$in = implode( ', ', $ids );

		$sql = "
			SELECT $properties->meta_id, meta_value
			FROM $properties->meta_table
			WHERE meta_key = %s
			AND $properties->meta_id IN ( $in )
		";

		if ( $args->orderby ) {
			if ( 'id' === $args->orderby ) {
				$args->orderby = $properties->meta_id;
			}

			$sql .= "ORDER BY $args->orderby $args->order";
		}

		$results = $wpdb->get_results( $wpdb->prepare( $sql, $meta_key ), ARRAY_A );

		$values = array();

		if ( is_array( $results ) ) {
			foreach ( $results as $row ) {
				if ( ! $args->data_type ) {
					$row['meta_value'] = maybe_unserialize( $row['meta_value'] );
				}

				$values[ $row[ $properties->meta_id ] ] = $row['meta_value'];
			}
		}

		return $values;
	}

}