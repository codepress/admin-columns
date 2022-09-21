<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;
use AC\View;

class CustomField extends Settings\Column {

	const NAME = 'custom_field';

	/**
	 * @var string
	 */
	private $field;

	protected function define_options() {
		return [ 'field' ];
	}

	/**
	 * @return View
	 */
	public function create_view() {
		$view = new View( [
			'label'   => __( 'Field', 'codepress-admin-columns' ),
			'setting' => $this->get_setting_field(),
		] );

		return $view;
	}

	protected function set_name() {
		$this->name = self::NAME;
	}

	private function use_text_field() {
		return (bool) apply_filters( 'ac/column/custom_field/use_text_input', false );
	}

	/**
	 * @return AC\Form\Element\Input
	 */
	private function get_settings_field_text() {
		return $this->create_element( 'text', 'field' )
		            ->set_attribute( 'placeholder', 'Custom field key' );
	}

	/**
	 * @return AC\Form\Element\Select
	 */
	private function get_settings_field_select() {
		$options = $this->get_field()
			? [ $this->get_field() => $this->get_field() ]
			: [];

		return $this->create_element( 'select', 'field' )
		            ->set_attribute( 'data-selected', $this->get_field() )
		            ->set_attribute( 'data-post_type', $this->get_post_type() )
		            ->set_attribute( 'data-type', $this->get_meta_type() )
		            ->set_options( $options )
		            ->set_attribute( 'class', 'custom_field' );
	}

	/**
	 * @return AC\Form\Element
	 */
	protected function get_setting_field() {
		return $this->use_text_field()
			? $this->get_settings_field_text()
			: $this->get_settings_field_select();
	}

	public function get_dependent_settings() {
		return [ new Settings\Column\CustomFieldType( $this->column ) ];
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
	 * @return string
	 */
	public function get_field() {
		return $this->field;
	}

	/**
	 * @param string $field
	 *
	 * @return self
	 */
	public function set_field( $field ) {

		// Backwards compatible for WordPress Settings API not storing fields starting with _
		if ( $field && 0 === strpos( $field, 'cpachidden' ) ) {
			$field = substr( $field, strlen( 'cpachidden' ) );
		}

		$this->field = $field;

		return $this;
	}

}