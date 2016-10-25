<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Taxonomy extends AC_Column_TaxonomyAbstract {

	private $post_type;

	public function set_post_type( $post_type ) {
		$this->post_type = $post_type;
	}

	public function get_post_type() {
		return $this->post_type;
	}

}