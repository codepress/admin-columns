<?php

/**
 * @since 2.0
 */
class AC_Column_Post_BeforeMoreTag extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-before_moretag' );
		$this->set_label( __( 'Before More Tag', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		$value = false;

		$p = get_post( $post_id );
		$extended = get_extended( $p->post_content );

		if ( ! empty( $extended['extended'] ) ) {
			$value = $extended['main'];
		}

		return $value;
	}

	public function register_settings() {
		$word_limit = new AC_Settings_Column_WordLimit( $this );
		$word_limit->set_default( 15 );

		$this->add_setting( $word_limit );
	}

}