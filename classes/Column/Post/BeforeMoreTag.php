<?php

/**
 * @since 2.0
 */
class AC_Column_Post_BeforeMoreTag extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-before_moretag' );
		$this->set_label( __( 'Before More Tag', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	public function get_raw_value( $post_id ) {
		$value = '';

		$p = get_post( $post_id );
		$extended = get_extended( $p->post_content );

		if ( ! empty( $extended['extended'] ) ) {

			// TODO: test
			$value = $this->get_settings()->word_limit->format( $extended['main'] );

			// TODO: remove
			//$value = ac_helper()->string->trim_words( $extended['main'], $this->get_option( 'excerpt_length' ) );
		}

		return $value;
	}

	public function register_settings() {
		$word_limit = new AC_Settings_Setting_WordLimit( $this );
		$word_limit->set_default( 15 );

		$this->add_setting( $word_limit );
	}

}