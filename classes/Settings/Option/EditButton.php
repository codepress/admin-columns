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
		return null === $this->get() || '1' === $this->get();
	}

}