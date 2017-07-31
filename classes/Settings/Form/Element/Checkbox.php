<?php

class AC_Settings_Form_Element_Checkbox extends AC_Settings_Form_Element {

	/**
	 * @var bool
	 */
	protected $vertical;

	protected function get_type() {
		return 'checkbox';
	}

	protected function get_classes() {
		$classes = array(
			$this->get_type() . '-labels',
		);

		if ( $this->is_vertical() ) {
			$classes[] = 'vertical';
		}

		return $classes;
	}

	public function render() {
		$elements = $this->get_elements();

		if ( ! $elements ) {
			return false;
		}

		$template = '<div class="%s-labels %s">%s</div>';

		return sprintf( $template, $this->get_type(), implode( ' ', $this->get_classes() ), implode( "\n", $elements ) );
	}

	private function get_elements() {
		if ( 'checkbox' === $this->get_type() ) {
			$this->set_name( $this->get_name() . '[]' );
		}

		$options = $this->get_options();

		if ( empty( $options ) ) {
			return null;
		}

		$elements = array();

		$value = $this->get_value();

		foreach ( $options as $key => $label ) {
			$input = new AC_Settings_Form_Element_Input( $this->get_name() );

			$input->set_value( $key )
			      ->set_type( $this->get_type() )
			      ->set_id( $this->get_id() . '-' . $key );

			if ( checked( $key, $value, false ) ) {
				$input->set_attribute( 'checked', 'checked' );
			}

			if ( is_array( $value ) && in_array( $key, $value ) ) {
				$input->set_attribute( 'checked', 'checked' );
			}

			$attributes = $this->get_attributes();

			$elements[] = sprintf( '<label %s>%s%s</label>', $this->get_attributes_as_string( $attributes ), $input->render(), esc_html( $label ) );
		}

		if ( $description = $this->render_description() ) {
			$elements[] = $description;
		}

		return $elements;
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