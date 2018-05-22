<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;

class Editing extends Settings\Column\Pro {

	protected function get_label() {
		return __( 'Editing', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( 'Edit your content directly from the overview.', 'codepress-admin-columns' );
	}

	protected function define_options() {
		return array( 'edit' );
	}

}