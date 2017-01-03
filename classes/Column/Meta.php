<?php

/**
 * Custom field column, displaying the contents of meta fields.
 * Suited for all list screens supporting WordPress' default way of handling meta data.
 *
 * Supports different types of meta fields, including dates, serialized data, linked content,
 * and boolean values.
 *
 * @since 1.0
 */
class AC_Column_Meta extends AC_Column
	implements AC_Column_MetaInterface {

	public function __construct() {
		$this->set_type( 'column-meta' );
		$this->set_label( __( 'Custom Field', 'codepress-admin-columns' ) );
		$this->set_group( 'custom_fields' );
	}

	// TODO: maybe obsolete?
	public function get_single_raw_value( $id ) {
		$array = $this->get_raw_value( $id );

		return $this->get_single_value( $array );
	}

	public function get_single_value( $array ) {
		return isset( $array[0] ) ? $array[0] : false;
	}

	/**
	 * @see AC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $id ) {
		$raw_value = '';

		if ( $field_key = $this->get_field_key() ) {
			$raw_value = get_metadata( $this->get_meta_type(), $id, $field_key, false );
		}

		return apply_filters( 'cac/column/meta/raw_value', $raw_value, $id, $field_key, $this );
	}

	protected function get_cache_key() {
		return $this->get_meta_type() . $this->get_post_type();
	}

	public function get_field_key() {
		return $this->get_settings()->custom_field->get_value();
	}

	/**
	 * @since 3.2.1
	 */
	public function get_field_type() {
		return $this->get_option( 'field_type' );
	}

	/**
	 * @since 3.2.1
	 */
	public function is_field_type( $type ) {
		return $type === $this->get_field_type();
	}

	/**
	 * @since 3.2.1
	 */
	public function is_field( $field ) {
		return $field === $this->get_field();
	}

	/**
	 * @since 3.2.1
	 */
	public function get_field() {
		return $this->get_field_key();
	}

	private function get_meta_table_properties() {
		global $wpdb;

		$table = false;

		switch ( $this->get_meta_type() ) {
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
		}

		if ( ! $table ) {
			return false;
		}

		// setup meta tables
		$q = new WP_Meta_Query();
		$q->get_sql( $this->get_meta_type(), $table, $id_column );

		return (object) array(
			'table'      => $table,
			'id'         => $id_column,
			'meta_id'    => $q->meta_id_column,
			'meta_table' => $q->meta_table,
		);
	}

	/**
	 * @return array
	 */
	public function get_meta() {
		global $wpdb;

		$p = $this->get_meta_table_properties();

		if ( ! $p ) {
			return array();
		}

		$sql = "
			SELECT DISTINCT mt.meta_key
			FROM $p->table AS t
			INNER JOIN $p->meta_table AS mt ON mt.$p->meta_id = t.$p->id 
			%s
			ORDER BY mt.meta_key ASC
		";

		$where = '';

		if ( 'post' === $this->get_meta_type() ) {
			$where = $wpdb->prepare( ' AND t.post_type = %s', $this->get_post_type() );
		}

		return $wpdb->get_results( sprintf( $sql, $where ), ARRAY_N );
	}

	/**
	 * Retrieve metadata object type (e.g., comment, post, or user)
	 *
	 * @since NEWVERSION
	 * @return bool
	 */
	public function get_meta_type() {
		return $this->get_list_screen()->get_meta_type();
	}

	/**
	 * @since 2.5.6
	 */
	public function get_username_by_id( $user_id ) {
		$username = false;

		if ( $user_id && is_numeric( $user_id ) && ( $userdata = get_userdata( $user_id ) ) ) {
			$username = $userdata->display_name;
		}

		return $username;
	}

	public function get_meta_values( $ids ) {
		return ac_helper()->meta->get_values_by_ids( $ids, $this->get_field_key(), $this->get_list_screen()->get_meta_type() );
	}

	/**
	 * @since 2.4.7
	 */
	public function get_meta_keys() {
		$keys = wp_cache_get( $this->get_cache_key(), 'cac_columns' );

		if ( ! $keys ) {
			$keys = $this->get_meta();

			wp_cache_add( $this->get_cache_key(), $keys, 'cac_columns', 12 );
		}

		if ( is_wp_error( $keys ) || empty( $keys ) ) {
			$keys = false;
		}

		// TODO: deprecate filters

		/**
		 * Filter the available custom field meta keys
		 * If showing hidden fields is enabled, they are prefixed with "cpachidden" in the list
		 *
		 * @since 2.0
		 *
		 * @param array $keys Available custom field keys
		 * @param AC_ListScreen $list_screen List screen class instance
		 */
		//$keys = apply_filters( 'cac/storage_model/meta_keys', $keys, $this->get_list_screen() );

		/**
		 * Filter the available custom field meta keys for this list screen type
		 *
		 * @since 2.0
		 * @see Filter cac/list_screen/meta_keys
		 */
		//$keys = apply_filters( "cac/storage_model/meta_keys/storage_key=" . $this->get_list_screen_key(), $keys, $this->get_list_screen() );

		/**
		 * Filter the available custom field meta keys
		 * If showing hidden fields is enabled, they are prefixed with "cpachidden" in the list
		 *
		 * @since NEWVERSION
		 *
		 * @param array $keys Available custom field keys
		 * @param AC_ListScreen $list_screen List screen class instance
		 */
		$keys = apply_filters( 'ac/column/custom_fields', $keys, $this );

		return $keys;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_Meta( $this ) );
		$this->add_setting( new AC_Settings_Setting_BeforeAfter( $this ) );
	}

	/**
	 * @since 1.0
	 *
	 * @param string $meta
	 *
	 * @return int[] Array with integers
	 */
	public function get_ids_from_meta( $meta ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->string->string_to_array_integers()' );

		return ac_helper()->string->string_to_array_integers( $meta );
	}

	/**
	 * Get meta by ID
	 *
	 * @since 1.0
	 *
	 * @param int $id ID
	 *
	 * @deprecated
	 * @return string Meta Value
	 */
	public function get_meta_by_id( $id ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->array->implode_recursive()' );

		return ac_helper()->array->implode_recursive( ', ', $this->get_raw_value( $id ) );
	}

}