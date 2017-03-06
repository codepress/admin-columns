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

	protected function get_cache_name() {
		return 'ac_settings_custom_field';
	}

	/**
	 * Get temp cache
	 */
	protected function get_cache() {
		wp_cache_get( $this->column->get_list_screen()->get_key(), $this->get_cache_name() );
	}

	/**
	 * @param array $data
	 * @param int   $expire Seconds
	 */
	protected function set_cache( $data, $expire = 15 ) {
		wp_cache_add( $this->column->get_list_screen()->get_key(), $data, $this->get_cache_name(), $expire );
	}

	/**
	 * @return array|false
	 */
	protected function get_meta_keys() {
		$keys = $this->get_cache();

		if ( ! $keys ) {
			$query = new AC_Meta_Query( $this->column, false );
			$query->select( 'meta_key' )
			      ->distinct()
			      ->order_by( 'meta_key' );

			if ( $this->column->get_post_type() ) {
				$query->where_post_type( $this->column->get_post_type() );
			}

			$keys = $query->get();

			$this->set_cache( $keys );
		}

		if ( empty( $keys ) ) {
			$keys = false;
		}

		/**
		 * @param array                           $keys Distinct meta keys from DB
		 * @param AC_Settings_Setting_CustomField $this
		 */
		return apply_filters( 'ac/column/custom_field/meta_keys', $keys, $this );
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

		// Backwards compatible for WordPress Settings API not storing fields starting with _
		$prefix_hidden = 'cpachidden';

		if ( 0 === strpos( $field, $prefix_hidden ) ) {
			$field = substr( $field, strlen( $prefix_hidden ) );
		}

		$this->field = $field;

		return true;
	}

}