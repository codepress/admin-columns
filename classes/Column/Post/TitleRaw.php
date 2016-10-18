<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.2.4
 */
class AC_Column_Post_TitleRaw extends AC_Column_PostAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-title_raw';
		$this->properties['label'] = __( 'Title without actions', 'codepress-admin-columns' );
	}

	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	function get_raw_value( $post_id ) {
		return get_post_field( 'post_title', $post_id );
	}

}