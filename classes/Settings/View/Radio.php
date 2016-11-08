<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_View_Radio
	implements AC_Settings_ViewInterface {

	/**
	 * @var AC_Settings_Form_Element_Radio
	 */
	protected $radio;

	/**
	 * @var bool
	 */
	protected $vertical;

	public function __construct( AC_Settings_Form_Element_Radio $radio ) {
		$this->radio = $radio;
		$this->vertical = false;
	}

	public function set_vertical( $vertical ) {
		$this->vertical = (bool) $vertical;
	}

	public function is_vertical() {
		return $this->vertical;
	}

	public function render() {
		$template = '<div class="radio-labels %s>%s</div>';
		$options = $this->radio->get_options();
		$vertical = $this->is_vertical() ? 'vertical' : '';

		if ( empty( $options ) ) {
			return;
		}

		return sprintf( $template, $vertical, $this->radio->render() );
	}

}