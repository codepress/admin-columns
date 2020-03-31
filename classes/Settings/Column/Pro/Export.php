<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;
use AC\View;

class Export extends Settings\Column\Pro {

	protected function get_label() {
		return __( 'Export', 'codepress-admin-columns' );
	}

	protected function get_instructions() {
		return ( new View() )->set_template( 'tooltip/export' );
	}

	protected function define_options() {
		return [ 'export' ];
	}

}