<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_Input extends AC_Settings_FieldAbstract
	implements AC_Settings_ViewInterface {

	/**
	 * @var bool
	 */
	protected $vertical;

	public function render_field() {
		$radio = $this->get_first_element();

		if ( ! $radio ) {
			return;
		}

		$options = $radio->get_options();

		if ( empty( $options ) ) {
			return;
		}

		$template = '<div class="radio-labels %s>%s</div>';
		$vertical = $this->is_vertical() ? 'vertical' : '';

		return sprintf( $template, $vertical, $radio->render() );
	}

	/**
	 * @param AC_Settings_Form_ElementAbstract $element
	 *
	 * @return $this
	 */
	public function add_element( AC_Settings_Form_ElementAbstract $element ) {
		// todo: add some form of notification when instance is not radio

		return parent::add_element( $element );
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