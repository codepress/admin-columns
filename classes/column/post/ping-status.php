<?php

/**
 * CPAC_Column_Post_Ping_Status
 *
 * @since 2.0
 */
class CPAC_Column_Post_Ping_Status extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {
		parent::init();

		$this->properties['type'] = 'column-ping_status';
		$this->properties['label'] = __( 'Ping Status', 'codepress-admin-columns' );
		$this->properties['object_property'] = 'ping_status';
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.2
	 */
	public function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {
		$ping_status = $this->get_raw_value( $post_id );
		$value = '<span class="dashicons dashicons-no cpac_status_no" title="' . $ping_status . '"></span>';
		if ( 'open' == $ping_status ) {
			$value = '<span class="dashicons dashicons-yes cpac_status_yes" title="' . $ping_status . '"></span>';
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {
		return get_post_field( 'ping_status', $post_id, 'raw' );
	}
}