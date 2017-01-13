<?php

class AC_Meta_Query {

	/**
	 * @var array
	 */
	private $args;

	/**
	 * @var false|WP_Meta_Query
	 */
	private $query;

	/**
	 * @var array
	 */
	private $fields = array( 'id', 'meta_key', 'meta_value' );

	public function __construct( array $args = array() ) {
		$this->args = array(
			'order'  => 'ASC',
			'fields' => $this->fields,
		);

		$this->set_args( $args );
	}

	public function is_valid_field( $field ) {
		return in_array( $field, $this->fields );
	}

	public function parse_orderby() {
		$orderby = $this->get( 'orderby' );

		if ( ! $orderby ) {
			return '';
		}

		if ( 'id' === $orderby ) {
			$orderby = $this->query->meta_id_column;
		}

		return sprintf( ' ORDER BY %s %s', $orderby, $this->get( 'order' ) );
	}

	public function parse_value( $value ) {
		if ( $this->get( 'unserialize' ) ) {
			$value = maybe_unserialize( $value );
		}

		return $value;
	}

	public function parse_fields() {
		$fields = $this->get( 'fields' );
		$id = array_search( 'id', $fields );

		if ( $id !== false ) {
			$fields[ $id ] = 'pt.' . $this->query->primary_id_column;
		}

		$distinct = $this->get( 'distinct' ) ? 'DISTINCT ' : '';

		return $distinct . implode( ', ', $fields );
	}

	public function get_results() {
		global $wpdb;

		if ( ! $this->query ) {
			return array();
		}

		$fields = $this->parse_fields();
		$orderby = $this->parse_orderby();
		$join_type = $this->get( 'show_empty' ) ? 'LEFT' : 'INNER';

		$query = $this->query;
		$type_id = $query->primary_id_column;

		$sql = "
			SELECT $fields
			FROM $query->primary_table AS pt
			$join_type JOIN $query->meta_table AS mt 
				ON mt.$query->meta_id_column = pt.$type_id AND mt.meta_key = %s
		";

		if ( $ids = $this->get( 'ids' ) ) {
			$in = implode( ', ', $ids );

			$sql .= " WHERE pt.$type_id IN( $in )";
		}

		$sql = $wpdb->prepare( $sql . $orderby, $this->get( 'key' ) );

		echo $sql;

		$results = $wpdb->get_results( $sql );

		if ( ! is_array( $results ) ) {
			return array();
		}

		$values = array();

		$return = $this->get( 'return' );

		switch ( $return ) {
			case 'id':
				foreach ( $results as $result ) {
					$values[] = $result->$type_id;
				}

				break;
			case 'meta_key':
				foreach ( $results as $result ) {
					$values[] = $result->meta_key;
				}

				break;
			case 'meta_value':
				foreach ( $results as $result ) {
					$values[] = $this->parse_value( $result->meta_value );
				}

				break;
			default:
				$ids = array();
				$single = $this->get( 'single' );

				foreach ( $results as $result ) {
					$id = $result->$type_id;

					if ( $single ) {
						if ( in_array( $id, $ids ) ) {
							continue;
						}

						$ids[] = $id;
					}

					$values[ $id ] = $this->parse_value( $result->meta_value );
				}
		}

		return $values;
	}

	public function get_args() {
		return $this->args;
	}

	public function set_args( array $args = array() ) {
		foreach ( $args as $key => $value ) {
			$this->set( $key, $value );
		}
	}

	public function get( $key ) {
		return isset( $this->args[ $key ] ) ? $this->args[ $key ] : null;
	}

	public function set( $key, $value ) {
		switch ( $key ) {
			case 'order':
				$value = 'ASC' === $value ? 'ASC' : 'DESC';

				break;
			case 'type':
				if ( ! $this->set_query( $value ) ) {
					$value = null;
				}

				break;
			case 'key':
				$value = sanitize_key( $value );

				break;
			case 'ids':
				$value = is_array( $value ) ? array_map( 'intval', $value ) : null;

				break;
			case 'return':
			case 'orderby':
				if ( ! $this->is_valid_field( $value ) ) {
					$value = null;
				}

				break;
			case 'fields':
				if ( ! is_array( $value ) ) {
					$value = array( $value );
				}

				foreach ( $value as $k => $field ) {
					if ( ! $this->is_valid_field( $field ) ) {
						unset( $value[ $k ] );
					}
				}

				$value = array_values( $value );

				if ( empty( $value ) ) {
					$value = $this->fields;
				}

				break;
			case 'show_empty': // add row even when no matching meta_key was found
			case 'single': // discard all values but the first (only works with no return value set)
			case 'unserialize': // try to unserialize a value
			case 'distinct': // return unique value (only works with a single field)
				$value = true === $value ? true : null;
		}

		$this->args[ $key ] = $value;
	}

	private function set_query( $type ) {
		global $wpdb;

		switch ( $type ) {
			case 'user':
				$table = $wpdb->users;
				$id = 'ID';

				break;
			case 'comment':
				$table = $wpdb->comments;
				$id = 'comment_ID';

				break;
			case 'post':
				$table = $wpdb->posts;
				$id = 'ID';

				break;
			default:
				return false;
		}

		$this->query = new WP_Meta_Query();
		$this->query->get_sql( $type, $table, $id );

		return true;
	}

}