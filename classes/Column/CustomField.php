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
class AC_Column_CustomField extends AC_Column_Meta {

	public function __construct() {
		$this->set_type( 'column-meta' );
		$this->set_label( __( 'Custom Field', 'codepress-admin-columns' ) );
		$this->set_group( 'custom_fields' );
	}

	public function get_meta_key() {
		return $this->get_setting( 'custom_field' )->get_value();
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_CustomField( $this ) );
		$this->add_setting( new AC_Settings_Setting_BeforeAfter( $this ) );
	}

	/**
	 * @since 2.4.7
	 */
	public function get_meta_keys() {
		global $wpdb;

		$keys = wp_cache_get( $this->list_screen->get_key(), 'ac_columns' );

		if ( ! $keys ) {
			$query = $this->get_meta_keys_query();

			if ( $query ) {
				$keys = $wpdb->get_results( $query, ARRAY_N );

				wp_cache_add( $this->list_screen->get_key(), $keys, 'ac_columns', 15 );
			}
		}

		if ( empty( $keys ) ) {
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

	/**
	 * Create query to fetch all unique meta keys from the correct meta table
	 *
	 * @return false|string
	 */
	private function get_meta_keys_query() {
		global $wpdb;

		$properties = $this->get_meta_table_properties();

		if ( ! $properties ) {
			return false;
		}

		$query = "SELECT DISTINCT mt.meta_key";

		if ( 'post' === $this->get_meta_type() ) {
			$query .= $wpdb->prepare( "
				FROM $properties->table AS t
				INNER JOIN $properties->meta_table AS mt ON mt.$properties->meta_id = t.$properties->id
				AND t.post_type = %s
			", $this->get_post_type() );
		} else {
			$query .= "FROM $properties->meta_table AS mt";
		}

		$query .= "ORDER BY mt.meta_key ASC";

		return $query;
	}

	/**
	 * @since 3.2.1
	 */
	public function get_field_type() {
		return $this->get_setting( 'field_type' )->get_value();
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
	public function get_field() {
		return $this->get_meta_key();
	}

	/**
	 * @since 3.2.1
	 */
	public function is_field( $field ) {
		return $field === $this->get_field();
	}

	/**
	 * Only valid for a Listscreen with a meta type
	 *
	 * @return mixed
	 */
	public function is_valid() {
		return $this->get_list_screen()->get_meta_type();
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