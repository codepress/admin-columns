<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_Message extends AC_Settings_FieldAbstract
	implements AC_Settings_ViewInterface {

	/**
	 * @var string
	 */
	protected $message;

	public function render_field() {
		$template = '<span class="%s">%s</span>';

		return sprintf( $template, esc_attr( $this->class ), $this->message );
	}

	/**
	 * @return string
	 */
	public function get_message() {
		if ( ! isset( $this->message ) ) {
			return false;
		}

		return $this->message;
	}

	/**
	 * @param string $message
	 *
	 * @return $this
	 */
	public function set_message( $message ) {
		$this->message = $message;

		return $this;
	}

}