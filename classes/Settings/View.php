<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_View extends AC_Settings_ViewAbstract {

	public function render() {
		$element = $this->get_first_element();

		if ( ! $element ) {
			return;
		}

		return $this->render_wrapper( $this->get_first_element()->render() );
	}

}