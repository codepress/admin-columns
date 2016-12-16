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
		} else {
			$field = $this->create_element( 'select', 'field' )
			              ->set_options( $this->get_field_options() );
		}

		$view = new AC_View( array(
			'label'   => __( 'Field', 'codepress-admin-columns' ),
			'setting' => $field,
		) );

		return $view;
	}

	private function get_field_options() {
		$options = array();

		/* @var AC_Column_CustomField $column */
		$column = $this->column;

		if ( $keys = $column->get_meta_keys() ) {
			$options = array(
				'hidden' => array(
					'title'   => __( 'Hidden Custom Fields', 'codepress-admin-columns' ),
					'options' => '',
				),
				'public' => array(
					'title'   => __( 'Custom Fields', 'codepress-admin-columns' ),
					'options' => '',
				),
			);

			foreach ( $keys as $field ) {
				$group = 0 === strpos( $field[0], '_' ) ? 'hidden' : 'public';

				$options[ $group ]['options'][ $field[0] ] = $field[0];
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