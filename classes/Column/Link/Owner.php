<?php

/**
 * @since 2.0
 */
class AC_Column_Link_Owner extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-owner' );
		$this->set_label( __( 'Owner', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		$value = $bookmark->link_owner;

		// add user link
		$userdata = get_userdata( $bookmark->link_owner );

		if ( ! empty( $userdata->data ) ) {
			$value = $userdata->data->user_nicename;
		}

		return $value;
	}

}