<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Form_View_Label
	implements AC_Settings_Form_ViewInterface {

	public function render() {
		$template = '<span class="%s">%s</span>';

		return sprintf( $template, esc_attr( $this->class ), $this->message );
	}

}