<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Form_Element_Radio extends AC_Settings_Form_ElementAbstract
	implements AC_Settings_Form_ViewInterface {

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

	public function render() {
		$template = '<label %s>%s%s</label>';
		$options = $this->get_options();

		if ( empty( $options ) ) {
			return;
		}

		$value = $this->get_value();

		$output = array();

		foreach ( $options as $key => $label ) {

			$input = new AC_Settings_Form_Element_Input();
			$input->set_name( $this->get_name() )
			      ->set_value( $key )
			      ->set_type( 'radio' )
			      ->set_id( $this->get_id() . '-' . $key );

			if ( checked( $key, $value, false ) ) {
				$input->set_attribute( 'checked', 'checked' );
			}

			$attributes = array();

			if ( $input->get_id() ) {
				$attributes['for'] = $input->get_id();
			}

			$output[] = sprintf( $template, $this->get_attributes_as_string( $attributes ), $input->render(), esc_html( $this->get_label() ) );
		}

		return implode( "\n", $output );
	}
}