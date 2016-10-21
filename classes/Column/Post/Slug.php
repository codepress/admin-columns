<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Slug extends AC_Column_PostAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-slug';
		$this->properties['label'] = __( 'Slug', 'codepress-admin-columns' );
	}

	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	function get_raw_value( $post_id ) {
		return get_post_field( 'post_name', $post_id, 'raw' );
	}

}