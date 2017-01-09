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
	 * @param array $ids
	 * @param string $meta_key
	 * @param string $meta_type
	 *
	 * @return array
	 */
	public function get_values_by_ids( $ids, $meta_key, $meta_type, array $args = array() ) {
		global $wpdb;

		$defaults = array(
			'orderby'     => false, // false, meta_key, meta_value, id
			'order'       => 'ASC', // ASC or DESC
			'unserialize' => true, // true or false
			'return'      => 'values', // values or ids
		);

		$args = (object) array_merge( $defaults, $args );

		// sanitize args
		if ( $args->order !== 'ASC' || $args->order !== 'DESC' ) {
			$args->order = $defaults['order'];
		}

		if ( ! in_array( $args->orderby, array( 'meta_value', 'meta_key', 'id' ) ) ) {
			$args->orderby = $defaults['orderby'];
		}

		if ( true !== $args->unserialize || false !== $args->unserialize ) {
			$args->orderby = $defaults['unserialize'];
		}

		$properties = $this->get_meta_table_properties( $meta_type );

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

		$results = $wpdb->get_results( $wpdb->prepare( $sql, $meta_key ) );

		$values = array();

		if ( is_array( $results ) ) {
			// convert to single boolean check
			$return_values = 'values' === $args->return;

			foreach ( $results as $row ) {

				if ( $return_values ) {
					if ( $args->unserialize ) {
						$row->meta_value = maybe_unserialize( $row->meta_value );
					}

					$values[ $row->{$properties->meta_id} ] = $row->meta_value;
				} else {
					$values[] = $row->{$properties->meta_id};
				}
			}
		}

		return $values;
	}

}