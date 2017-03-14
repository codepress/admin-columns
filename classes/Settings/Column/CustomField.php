<?php

class AC_Settings_Column_CustomField extends AC_Settings_Column_Meta {

	protected function set_name() {
		$this->name = 'custom_field';
	}

	public function create_view() {

		/**
		 * @deprecated NEWVERSION
		 */
		$use_text_input = apply_filters_deprecated( 'cac/column/meta/use_text_input', array( false ), 'NEWVSERION', 'ac/column/custom_field/use_text_input' );

		/**
		 * DOM can get overloaded when dropdown contains to many custom fields. Use this filter to replace the dropdown with a text input.
		 *
		 * @since NEWVERSION
		 *
		 * @param bool false
		 */
		if ( apply_filters( 'ac/column/custom_field/use_text_input', $use_text_input ) ) {
			$field = $this->create_element( 'text', 'field' )
			              ->set_attribute( 'placeholder', 'Custom field key' );
		} else {
			$field = $this->get_setting_field();
			$field->set_no_result( __( 'No custom fields available.', 'codepress-admin-columns' ) . ' ' . sprintf( __( 'Please create a %s item first.', 'codepress-admin-columns' ), '<strong>' . $this->column->get_list_screen()->get_singular_label() . '</strong>' ) );
		}

		$view = new AC_View( array(
			'label'   => __( 'Field', 'codepress-admin-columns' ),
			'setting' => $field,
		) );

		return $view;
	}

	public function get_dependent_settings() {
		return array( new AC_Settings_Column_CustomFieldType( $this->column ) );
	}

	protected function get_cache_group() {
		return 'ac_settings_custom_field';
	}

	/**
	 * @return string
	 */
	protected function get_cache_key() {
		return parent::get_cache_key() . $this->get_post_type();
	}

	protected function get_meta_type() {
		return $this->column->get_list_screen()->get_meta_type();
	}

	/**
	 * @return string Post type
	 */
	protected function get_post_type() {
		return $this->column->get_post_type();
	}

	/**
	 * @return array|false
	 */
	protected function get_keys() {
		$query = new AC_Meta_Query( $this->get_meta_type() );

		$query->select( 'meta_key' )
		      ->distinct()
		      ->order_by( 'meta_key' );

		if ( $this->get_post_type() ) {
			$query->where_post_type( $this->get_post_type() );
		}

		$keys = $query->get();

		if ( empty( $keys ) ) {
			$keys = false;
		}

		/**
		 * @param array                          $keys Distinct meta keys from DB
		 * @param AC_Settings_Column_CustomField $this
		 */
		return apply_filters( 'ac/column/custom_field/meta_keys', $keys, $this );
	}

	/**
	 * @param string $field
	 *
	 * @return bool
	 */
	public function set_field( $field ) {

		// Backwards compatible for WordPress Settings API not storing fields starting with _
		$prefix_hidden = 'cpachidden';

		if ( 0 === strpos( $field, $prefix_hidden ) ) {
			$field = substr( $field, strlen( $prefix_hidden ) );
		}

		return parent::set_field( $field );
	}

}