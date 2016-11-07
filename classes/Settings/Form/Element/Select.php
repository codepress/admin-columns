<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Form_Element_Select extends AC_Settings_Form_ElementAbstract {

	/**
	 * @var array
	 */
	protected $options;

	public function __construct() {
		$this->options = array();
	}

	public function set_options( array $options ) {
		$this->options = $options;

		return $this;
	}

	public function get_options() {
		return $this->options;
	}

	protected function render_options( array $options ) {
		$template = '<option %s>%s</option>';
		$output = array();

		foreach ( $options as $key => $option ) {
			if ( isset( $option['options'] ) && is_array( $option['options'] ) ) {
				$output[] = $this->render_optgroup( $option );

				continue;
			}

			$attributes = array();

			if ( selected( $key, $this->get_value(), false ) ) {
				$attributes['selected'] = 'selected';
			}

			$output[] = sprintf( $template, $this->get_attributes_as_string( $attributes ), esc_html( $option ) );
		}

		return implode( "\n", $output );
	}

	protected function render_optgroup( array $group ) {
		$template = '<optgroup %s>%s</optgroup>';
		$attributes = array();

		if ( isset( $group['title'] ) ) {
			$attributes['label'] = esc_attr( $group['title'] );
		}

		return sprintf( $template, $this->get_attributes_as_string( $attributes ), $this->render_options( $group['options'] ) );
	}

	public function render() {
		$template = '<select %s>%s</select>';

		$attributes = $this->get_attributes();
		$attributes['name'] = $this->get_name();

		return sprintf( $template, $this->get_attributes_as_string( $attributes ), $this->render_options( $this->get_options() ) );
	}

}