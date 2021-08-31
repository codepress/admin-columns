<?php

namespace AC\Settings\Option;

use AC\Settings\Option;

class EditButton extends Option {

	public function __construct() {
		parent::__construct( 'show_edit_button' );
	}

	/**
	 * @return bool
	 */
	public function is_enabled() {
		return in_array( $this->get(), [ null, '1' ], true );
	}

}