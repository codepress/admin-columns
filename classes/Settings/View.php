<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_View extends AC_Settings_ViewAbstract {

	public function render() {
		if ( ! $this->get_elements() ) {
			return;
		}

		return $this->render_layout();
	}

}