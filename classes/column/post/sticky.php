<?php

/**
 * CPAC_Column_Post_Sticky
 *
 * @since 2.0
 */
class CPAC_Column_Post_Sticky extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {
		parent::init();

		$this->properties['type'] = 'column-sticky';
		$this->properties['label'] = __( 'Sticky', 'codepress-admin-columns' );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0
	 */
	function apply_conditional() {
		return 'post' == $this->get_post_type();
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id ) ? '<span class="dashicons dashicons-yes cpac_status_yes"></span>' : '<span class="dashicons dashicons-no cpac_status_no"></span>';
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {
		return is_sticky( $post_id );
	}
}