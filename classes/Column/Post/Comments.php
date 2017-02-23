<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Comments extends AC_Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'comments' );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

}