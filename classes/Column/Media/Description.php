<?php

namespace AC\Column\Media;

use AC\Column;

/**
 * @since 2.0
 */
class Description extends Column\Post\Content {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-description' );
		$this->set_label( __( 'Description', 'codepress-admin-columns' ) );
	}

}