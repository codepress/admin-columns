<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4
 */
class AC_Column_Post_Content extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-content' );
		$this->set_label( __( 'Content', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		return $this->get_setting( 'word_limit' )->format( $this->get_raw_value( $post_id ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_content', $post_id, 'raw' );
	}

	public function register_settings() {
		parent::register_settings();

		$this->add_setting( new AC_Settings_Setting_WordLimit( $this ) );
	}

}