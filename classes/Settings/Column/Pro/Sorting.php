<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;
use AC\View;

class Sorting extends Settings\Column\Pro {

	protected function get_label() {
		return __( 'Sorting', 'codepress-admin-columns' );
	}

	protected function get_instructions() {
		return ( new View() )->set_template( 'tooltip/sorting' );
	}

	protected function define_options() {
		return [ 'sort' ];
	}

}