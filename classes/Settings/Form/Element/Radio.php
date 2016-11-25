<?php

final class AC_Settings_Form_Element_Radio extends AC_Settings_Form_ElementAbstract
	implements AC_ViewInterface {

	/**
	 * @var bool
	 */
	protected $vertical;

	public function render() {
		$options = $this->get_options();

		if ( empty( $options ) ) {
			return;
		}

		$template = '<div class="radio-labels %s">%s</div>';
		$vertical = $this->is_vertical() ? 'vertical' : '';
		$value = $this->get_value();
		$elements = array();

		foreach ( $options as $key => $label ) {
			$input = new AC_Settings_Form_Element_Input( $this->get_name() );
			$input->set_value( $key )
			      ->set_type( 'radio' )
			      ->set_id( $this->get_id() . '-' . $key );

			if ( checked( $key, $value, false ) ) {
				$input->set_attribute( 'checked', 'checked' );
			}

			$attributes = array();

			if ( $input->get_id() ) {
				$attributes['for'] = $input->get_id();
			}

			$elements[] = sprintf( '<label %s>%s%s</label>', $this->get_attributes_as_string( $attributes ), $input->render(), esc_html( $label ) );
		}

		return sprintf( $template, $vertical, implode( "\n", $elements ) );
	}

	public function set_vertical( $vertical ) {
		$this->vertical = (bool) $vertical;
	}

	public function is_vertical() {
		if ( empty( $this->vertical ) ) {
			return false;
		}

		return $this->vertical;
	}

}