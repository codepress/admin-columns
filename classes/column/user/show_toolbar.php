<?php


class CPAC_Column_User_Show_Toolbar extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user_show_toolbar';
		$this->properties['label'] = __( 'Show Toolbar', 'codepress-admin-columns' );
	}

	function get_value( $user_id ) {
		$raw_value = $this->get_raw_value( $user_id );
		$value = ( empty( $raw_value ) || 'false' === $raw_value || '0' === $raw_value ) ? '<span class="dashicons dashicons-no cpac_status_no"></span>' : '<span class="dashicons dashicons-yes cpac_status_yes"></span>';
		return $value;
	}

	function get_raw_value( $user_id ) {
		$userdata = get_userdata( $user_id );
		
		return $userdata->show_admin_bar_front;
	}
}