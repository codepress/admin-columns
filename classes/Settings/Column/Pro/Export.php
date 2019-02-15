<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;

class Export extends Settings\Column\Pro {

	protected function get_label() {
		return __( 'Export', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( 'Export your column data to CSV.', 'codepress-admin-columns' );
	}

	protected function define_options() {
		return array( 'export' );
	}

}