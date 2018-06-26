<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;

class Sorting extends Settings\Column\Pro {

	protected function get_label() {
		return __( 'Sorting', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( "This will make the column sortable.", 'codepress-admin-columns' );
	}

	protected function define_options() {
		return array( 'sort' );
	}

}