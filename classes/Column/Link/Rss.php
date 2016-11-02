<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_Rss extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-rss' );
		$this->set_label( __( 'Rss', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return ac_helper()->string->shorten_url( $bookmark->link_rss );
	}

}