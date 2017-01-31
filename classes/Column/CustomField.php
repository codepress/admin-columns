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
		$this->set_group( 'custom_field' );
	}

	/**
	 * Display value
	 *
	 * @param int $object_id ID
	 *
	 * @return mixed
	 */
	public function get_value( $object_id ) {

		// Count does not need formatting
		if ( 'count' === $this->get_field_type() ) {
			return count( $this->get_meta_value( $object_id, $this->get_meta_key(), false ) );
		}

		return $this->format_value( $this->get_raw_value( $object_id ) );
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
	public function get_field() {
		return $this->get_meta_key();
	}

	/**
	 * Only valid for a Listscreen with a meta type
	 *
	 * @return mixed
	 */
	public function is_valid() {
		return in_array( $this->get_list_screen()->get_meta_type(), array( 'post', 'user', 'comment', 'term' ) );
	}

}