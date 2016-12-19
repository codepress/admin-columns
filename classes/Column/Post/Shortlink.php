<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Shortlink extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-shortlink' );
		$this->set_label( __( 'Shortlink', 'codepress-admin-columns' ) );
	}

	function get_value( $post_id ) {
		$link = $this->get_raw_value( $post_id );

		return ac_helper()->html->link( $link, $link );
	}

	function get_raw_value( $post_id ) {
		return wp_get_shortlink( $post_id );
	}

}