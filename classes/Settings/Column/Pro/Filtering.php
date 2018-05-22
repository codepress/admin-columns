<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;

class Filtering extends Settings\Column\Pro {

	protected function get_label() {
		return __( 'Filtering', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( "This will make the column filterable.", 'codepress-admin-columns' );
	}

	protected function define_options() {
		return array( 'filter' );
	}

}