<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_PingStatus extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-ping_status' );
		$this->set_label( __( 'Ping Status', 'codepress-admin-columns' ) );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

	public function get_value( $post_id ) {
		$ping_status = $this->get_raw_value( $post_id );

		return ac_helper()->icon->yes_or_no( 'open' == $ping_status, $ping_status );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'ping_status', $post_id, 'raw' );
	}

}