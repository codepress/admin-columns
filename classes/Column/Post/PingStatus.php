<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_PingStatus extends AC_Column_PostAbstract {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-ping_status';
		$this->properties['label'] = __( 'Ping Status', 'codepress-admin-columns' );
	}

	public function apply_conditional() {
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