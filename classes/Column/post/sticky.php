<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Post_Sticky
 *
 * @since 2.0
 */
class CPAC_Column_Post_Sticky extends CPAC_Column {

	private $stickies = null;

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {
		parent::init();

		$this->properties['type'] = 'column-sticky';
		$this->properties['label'] = __( 'Sticky', 'codepress-admin-columns' );
	}

	function apply_conditional() {
		return 'post' == $this->get_post_type();
	}

	function get_value( $post_id ) {
		return $this->get_icon_yes_or_no( $this->is_sticky( $post_id ) );
	}

	function get_raw_value( $post_id ) {
		return $this->is_sticky( $post_id );
	}

	// Helpers
	private function is_sticky( $post_id ) {
		if ( null === $this->stickies ) {
			$this->stickies = get_option( 'sticky_posts' );
		}

		return in_array( $post_id, (array) $this->stickies );
	}
}