<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Title extends AC_Column_DefaultPostAbstract {

	public function __construct() {
		parent::__construct();
		$this->set_type( 'title' );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'title' );
	}

}