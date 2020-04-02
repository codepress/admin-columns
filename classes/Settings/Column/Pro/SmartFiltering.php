<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;
use AC\View;

class SmartFiltering extends Settings\Column\Pro {

	protected function get_label() {
		return __( 'Smart Filtering', 'codepress-admin-columns' );
	}

	protected function get_instructions() {
		return ( new View() )->set_template( 'tooltip/smart-filtering' );
	}

	protected function define_options() {
		return [ 'smart-filtering' ];
	}

}