<?php

namespace AC\Column\User;

use AC\Column;

class RichEditing extends Column {

	public function __construct() {
		$this->set_type( 'column-rich_editing' );
		$this->set_label( __( 'Visual Editor', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		return ac_helper()->icon->yes_or_no( $this->has_rich_editing( $user_id ) );
	}

	public function get_raw_value( $user_id ) {
		return $this->has_rich_editing( $user_id );
	}

	private function has_rich_editing( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->rich_editing == 'true' ? true : false;
	}

}