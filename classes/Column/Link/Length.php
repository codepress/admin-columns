<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_Length extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-length' );
		$this->set_label( __( 'Length', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return strlen( $bookmark->link_name );
	}

}