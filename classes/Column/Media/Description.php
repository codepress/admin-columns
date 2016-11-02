<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_Description extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-description' );
		$this->set_label( __( 'Description', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		return get_post_field( 'post_content', $id );
	}

}