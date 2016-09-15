<?php
defined( 'ABSPATH' ) or die();

class AC_Column_User_RichEditing extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-rich_editing';
		$this->properties['label'] = __( 'Visual Editor', 'codepress-admin-columns' );
	}

	function get_value( $user_id ) {
		return ac_helper()->icon->yes_or_no( $this->has_rich_editing( $user_id ) );
	}

	function get_raw_value( $user_id ) {
		return $this->has_rich_editing( $user_id );
	}

	private function has_rich_editing( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->rich_editing == 'true' ? true : false;
	}

}