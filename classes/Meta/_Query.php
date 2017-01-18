<?php

class _AC_Meta_Query {

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

	/**
	 * AC_Meta_Query constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = array() ) {
		$this->args = array(
			'orderby'      => false,
			'order'        => 'ASC',
			'type'         => false,
			'key'          => false,
			'ids'          => array(),
			'return'       => false,
			'fields'       => $this->fields,
			'empty_keys'   => false, // allow objects that do not have this meta_key
			'empty_values' => true, // allow objects that do have this meta_key but are empty
			'single'       => false, // discard all values but the first (only works when return is set to false)
			'unserialize'  => false, // try to unserialize each value
			'distinct'     => false, // return unique values (only works with a single field)
		);

		$this->set_args( $args );
	}

	public function is_valid_field( $field ) {
		return in_array( $field, $this->fields );
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

	/**
	 * Configure meta query
	 *
	 * @param string $key
	 * @param string|bool|array $value
	 *
	 * @return bool
	 */
	public function set( $key, $value ) {
		switch ( $key ) {
			case 'order':
				$value = 'ASC' === $value ? 'ASC' : 'DESC';

				break;
			case 'type':
				if ( ! $this->set_query( $value ) ) {
					return false;
				}

				break;
			case 'key':
				$value = sanitize_key( $value );

				break;
			case 'ids':
				if ( ! is_array( $value ) ) {
					return false;
				}

				$value = array_map( 'intval', $value );

				break;
			case 'return':
			case 'orderby':
				if ( ! $this->is_valid_field( $value ) ) {
					return false;
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
					return false;
				}

				break;
			case 'post_type':
				if ( ! post_type_exists( $value ) ) {
					return false;
				}

				break;
			case 'empty_keys':
			case 'empty_value':
			case 'single':
			case 'unserialize':
			case 'distinct':
				if ( ! is_bool( $value ) ) {
					return false;
				}
		}

		$this->args[ $key ] = $value;

		return true;
	}

	public function get_results() {
		global $wpdb;

		if ( ! $this->query ) {
			return array();
		}

		$args = (object) $this->get_args();

		$fields = $this->parse_fields();

		$join_type = $args->empty_keys ? 'LEFT' : 'INNER';
		$join_values = $args->empty_values ? '' : " AND mt.meta_value != ''";
		$join_key = $args->key ? $wpdb->prepare( " AND mt.meta_key = %s", $args->key ) : '';

		$query = $this->query;
		$type_id = $query->primary_id_column;

		$sql = "
			SELECT $fields
			FROM $query->primary_table AS pt
			$join_type JOIN $query->meta_table AS mt
				ON mt.$query->meta_id_column = pt.$type_id $join_key $join_values
			WHERE 1=1
		";

		if ( $ids = $args->ids ) {
			$in = implode( ', ', $ids );

			$sql .= " AND pt.$type_id IN( $in )";
		}

		if ( $args->post_type ) {
			$sql .= $wpdb->prepare( " AND pt.post_type = %s", $args->post_type );
		}

		$sql .= $this->parse_orderby();

		echo $sql;

		$results = $wpdb->get_results( $sql );

		if ( ! is_array( $results ) ) {
			return array();
		}

		$values = array();

		$return = $args->return;
		$fields = $args->fields;

		// match return when there is a single field
		if ( 1 === count( $fields ) ) {
			$return = current( $fields );
		}

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
				$single = $args->single;

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

	public function parse_orderby() {
		$orderby = $this->get( 'orderby' );

		if ( ! $orderby ) {
			return '';
		}

		if ( 'id' === $orderby ) {
			$orderby = $this->query->primary_id_column;
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