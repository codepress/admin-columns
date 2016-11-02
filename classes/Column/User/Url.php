<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_Url extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user_url';
		$this->properties['label'] = __( 'Url', 'codepress-admin-columns' );
	}

	function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->user_url;
	}

}