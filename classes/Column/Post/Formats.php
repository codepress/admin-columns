<?php

/**
 * @since 2.0
 */
class AC_Column_Post_Formats extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-post_formats' );
		$this->set_label( __( 'Post Format', 'codepress-admin-columns' ) );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'post-formats' );
	}

	public function get_raw_value( $post_id ) {
		return get_post_format( $post_id );
	}

	public function get_taxonomy() {
		return 'post_format';
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_PostFormatIcon( $this ) );
	}

}