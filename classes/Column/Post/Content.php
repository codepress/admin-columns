<?php

/**
 * @since 2.4
 */
class AC_Column_Post_Content extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-content' );
		$this->set_label( __( 'Content', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_content', $post_id, 'raw' );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_WordLimit( $this ) );
		$this->add_setting( new AC_Settings_Column_BeforeAfter( $this ) );
	}

}