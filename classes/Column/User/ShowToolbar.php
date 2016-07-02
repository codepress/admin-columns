<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_User_ShowToolbar extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user_show_toolbar';
		$this->properties['label'] = __( 'Show Toolbar', 'codepress-admin-columns' );
	}

	public function get_value( $user_id ) {
		return $this->get_icon_yes_or_no( 'true' == $this->show_admin_bar_front( $user_id ) );
	}

	public function get_raw_value( $user_id ) {
		return $this->show_admin_bar_front( $user_id );
	}

	private function show_admin_bar_front( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->show_admin_bar_front;
	}

}