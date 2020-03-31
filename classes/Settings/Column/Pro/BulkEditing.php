<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;
use AC\View;

class BulkEditing extends Settings\Column\Pro {

	protected function get_label() {
		return __( 'Bulk Editing', 'codepress-admin-columns' );
	}

	protected function get_instructions() {
		return ( new View() )->set_template( 'tooltip/bulk-editing' );
	}

	protected function define_options() {
		return [ 'bulk-edit' ];
	}

}