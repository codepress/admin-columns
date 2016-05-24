<?php
/**
 *
 * @since NEWVERSION
 */
class CPAC_Column_User_Show_Toolbar extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user_show_toolbar';
		$this->properties['label'] = __( 'Show Toolbar', 'codepress-admin-columns' );
	}

	function get_value( $user_id ) {
		return $this->get_raw_value( $user_id ) == 'true' ? '<span class="dashicons dashicons-yes cpac_status_yes"></span>' : '<span class="dashicons dashicons-no cpac_status_no"></span>';
	}

	function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->show_admin_bar_front;
	}
}