<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_PageTemplate extends AC_ColumnPostAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-page_template';
		$this->properties['label'] = __( 'Page Template', 'codepress-admin-columns' );
	}

	function get_value( $post_id ) {
		return array_search( $this->get_raw_value( $post_id ), get_page_templates() );
	}

	function get_raw_value( $post_id ) {
		return get_post_meta( $post_id, '_wp_page_template', true );
	}

	function apply_conditional() {
		return 'page' === $this->get_post_type();
	}

}