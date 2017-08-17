<?php

/**
 * @since 2.2.4
 */
class AC_Column_Post_TitleRaw extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-title_raw' );
		$this->set_label( __( 'Title Only', 'codepress-admin-columns' ) );
	}

	function get_raw_value( $post_id ) {
		return get_post_field( 'post_title', $post_id );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_PostLink( $this ) );
	}

}