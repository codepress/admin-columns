<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Categories extends AC_Column_Default {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'categories' );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 15 );
	}

	public function get_taxonomy() {
		return 'category';
	}

	public function is_valid() {
		return ac_helper()->taxonomy->is_taxonomy_registered( $this->get_post_type(), $this->get_taxonomy() );
	}

}