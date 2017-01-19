<?php

class AC_Settings_Setting_CustomField extends AC_Settings_Setting {

	/**
	 * @var string
	 */
	private $field;

	public function get_dependent_settings() {
		return array( new AC_Settings_Setting_CustomFieldType( $this->column ) );
	}

	protected function set_name() {
		$this->name = 'custom_field';
	}

	protected function define_options() {
		return array( 'field' );
	}

	public function create_view() {
		// DOM can get overloaded when dropdown contains to many custom fields. Use this filter to replace the dropdown with a text input.
		if ( apply_filters( 'cac/column/meta/use_text_input', false ) ) {
			$field = $this->create_element( 'text', 'field' )
			              ->set_attribute( 'placeholder', 'Custom field key' );
		}
		else {
			$field = $this->create_element( 'select', 'field' )
			              ->set_options( $this->get_field_options() )
			              ->set_no_result( __( 'No custom fields available.', 'codepress-admin-columns' ) . ' ' . sprintf( __( 'Please create a %s item first.', 'codepress-admin-columns' ), '<strong>' . $this->column->get_list_screen()->get_singular_label() . '</strong>' ) );
		}

		$view = new AC_View( array(
			'label'   => __( 'Field', 'codepress-admin-columns' ),
			'setting' => $field,
		) );

		return $view;
	}

	private function get_meta_keys() {
		$cache_key = $this->column->get_list_screen()->get_key();

		$keys = wp_cache_get( $cache_key, 'ac_settings_custom_field' );

		if ( ! $keys ) {
			$query = new AC_Meta_Query( $this->column, false );
			$query->select( 'meta_key' )
			      ->distinct()
			      ->order_by( 'meta_key' );

			if ( $this->column->get_post_type() ) {
				$query->where_post_type( $this->column->get_post_type() );
			}

			$keys = $query->get();

			if ( $keys ) {
				wp_cache_add( $cache_key, $keys, 'ac_settings_custom_field', 15 );
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

		// TODO: change name?
		$keys = apply_filters( 'ac/column/custom_fields', $keys, $this );

		return $keys;
	}

	private function get_field_options() {
		$options = array();

		if ( $keys = $this->get_meta_keys() ) {
			$options = array(
				'hidden' => array(
					'title'   => __( 'Hidden Custom Fields', 'codepress-admin-columns' ),
					'options' => array(),
				),
				'public' => array(
					'title'   => __( 'Custom Fields', 'codepress-admin-columns' ),
					'options' => array(),
				),
			);

			foreach ( $keys as $field ) {
				$group = 0 === strpos( $field[0], '_' ) ? 'hidden' : 'public';

				$options[ $group ]['options'][ $field ] = $field;
			}

			krsort( $options ); // public first
		}

		return $options;
	}

	/**
	 * @return string
	 */
	public function get_field() {
		return $this->field;
	}

	/**
	 * @param string $field
	 *
	 * @return bool
	 */
	public function set_field( $field ) {
		/**
		 * Backcompat for WordPress Settings API not storing fields starting with _
		 */
		$prefix_hidden = 'cpachidden';

		if ( 0 === strpos( $field, $prefix_hidden ) ) {
			$field = substr( $field, strlen( $prefix_hidden ) );
		}

		$this->field = $field;

		return true;
	}

}