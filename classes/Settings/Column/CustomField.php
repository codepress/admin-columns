<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;

class CustomField extends Meta {

	protected function set_name() {
		$this->name = 'custom_field';
	}

	protected function get_setting_field() {
		$use_text_input = apply_filters( 'ac/column/custom_field/use_text_input', false );

		if ( $use_text_input ) {
			return $this->create_element( 'text', 'field' )->set_attribute( 'placeholder', __( 'Custom field key', 'codepress-admin-columns' ) );
		}

		$field = parent::get_setting_field();
		$field->set_no_result( __( 'No custom fields available.', 'codepress-admin-columns' ) . ' ' . sprintf( __( 'Please create a %s item first.', 'codepress-admin-columns' ), '<strong>' . $this->column->get_list_screen()->get_singular_label() . '</strong>' ) );

		return $field;
	}

	public function get_dependent_settings() {
		return array( new Settings\Column\CustomFieldType( $this->column ) );
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
	protected function get_meta_keys() {
		$query = new AC\Meta\Query( $this->get_meta_type() );

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
		 * @param array                       $keys Distinct meta keys from DB
		 * @param Settings\Column\CustomField $this
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
		if ( 0 === strpos( $field, 'cpachidden' ) ) {
			$field = substr( $field, strlen( 'cpachidden' ) );
		}

		return parent::set_field( $field );
	}

}