<?php

namespace AC\Column\Media;

use AC\Column;

class Description extends Column\Post\Content {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-description' )
		     ->set_label( __( 'Description', 'codepress-admin-columns' ) );
	}

}