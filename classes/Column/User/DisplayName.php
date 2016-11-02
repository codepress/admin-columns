<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_DisplayName extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-display_name';
		$this->properties['label'] = __( 'Display name', 'codepress-admin-columns' );
	}

	function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->display_name;
	}

}