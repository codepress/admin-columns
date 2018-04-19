<?php

namespace AC\Column\Media;

use AC\Column;

/**
 * @since 2.0
 */
class Width extends Column\Media\Height {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-width' );
		$this->set_label( __( 'Width', 'codepress-admin-columns' ) );
	}

	protected function get_option_name() {
		return 'width';
	}

}