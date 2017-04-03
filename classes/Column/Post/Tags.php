<?php

/**
 * @since 3.0
 */
class AC_Column_Post_Tags extends AC_Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'tags' );
	}

	public function register_settings() {
		$this->get_setting( 'width' )->set_default( 15 );
	}

	public function get_taxonomy() {
		return 'post_tag';
	}

	public function is_valid() {
		return ac_helper()->taxonomy->is_taxonomy_registered( $this->get_post_type(), $this->get_taxonomy() );
	}

}