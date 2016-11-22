<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Categories extends AC_Column_DefaultPostAbstract {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'categories' );
	}

	public function get_default_with() {
		return 15;
	}

	public function get_taxonomy() {
		return 'category';
	}

	public function is_valid() {
		return ac_helper()->taxonomy->is_taxonomy_registered( $this->get_post_type(), $this->get_taxonomy() );
	}

}