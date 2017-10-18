<?php

/**
 * @since 2.0
 */
class AC_Column_Post_ID extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-postid' );
		$this->set_label( __( 'ID', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return $post_id;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_BeforeAfter( $this ) );
	}

}