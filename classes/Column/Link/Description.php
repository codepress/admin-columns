<?php

/**
 * @since 2.0
 */
class AC_Column_Link_Description extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-description' );
		$this->set_label( __( 'Description', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return $bookmark->link_description;
	}

}