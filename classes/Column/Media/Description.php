<?php

/**
 * @since 2.0
 */
class AC_Column_Media_Description extends AC_Column_Post_Content {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-description' );
		$this->set_label( __( 'Description', 'codepress-admin-columns' ) );
	}

}