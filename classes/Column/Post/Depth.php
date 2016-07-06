<?php
defined( 'ABSPATH' ) or die();

/**
 * Depth of the current page (number of ancestors + 1)
 *
 * @since 2.3.4
 */
class AC_Column_Post_Depth extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-depth';
		$this->properties['label'] = __( 'Depth', 'codepress-admin-columns' );
	}

	public function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	public function get_raw_value( $post_id ) {
		return count( get_post_ancestors( $post_id ) ) + 1;
	}

	public function apply_conditional() {
		return is_post_type_hierarchical( $this->get_post_type() );
	}

}