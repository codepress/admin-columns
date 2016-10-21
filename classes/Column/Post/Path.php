<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying path (without URL, e.g. "/my-category/sample-post/") to the front-end location of this item.
 *
 * @since 2.2.3
 */
class AC_Column_Post_Path extends AC_Column_PostAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-path';
		$this->properties['label'] = __( 'Path', 'codepress-admin-columns' );
	}

	public function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	public function get_raw_value( $post_id ) {
		return str_replace( home_url(), '', get_permalink( $post_id ) );
	}

}