<?php

/**
 * @since NEWVERSION
 */
class AC_Column_User_ShowToolbar extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-user_show_toolbar' );
		$this->set_label( __( 'Show Toolbar', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		return ac_helper()->icon->yes_or_no( 'true' == $this->show_admin_bar_front( $user_id ) );
	}

	public function get_raw_value( $user_id ) {
		return $this->show_admin_bar_front( $user_id );
	}

	private function show_admin_bar_front( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->show_admin_bar_front;
	}

}