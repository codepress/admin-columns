<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Form_View_Message {

	/**
	 * @var $string
	 */
	protected $message;

	/**
	 * @var string
	 */
	protected $class;

	public function __construct( $message, $class = '' ) {
		$this->message = $message;
		$this->class = $class;
	}

	public function render() {
		$template = '<span class="%s">%s</span>';

		return sprintf( $template, esc_attr( $this->class ), $this->message );
	}

}