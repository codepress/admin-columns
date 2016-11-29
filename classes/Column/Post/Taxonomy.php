<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Taxonomy extends AC_Column_Taxonomy {

	/**
	 * @return bool True when post type has associated taxonomies
	 */
	public function is_valid() {
		return get_object_taxonomies( $this->get_post_type() ) ? true : false;
	}

}