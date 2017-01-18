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