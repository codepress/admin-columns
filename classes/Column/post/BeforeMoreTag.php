<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_BeforeMoreTag extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type']	 = 'column-before_moretag';
		$this->properties['label']	 = __( 'Before More Tag', 'codepress-admin-columns' );

		$this->options['excerpt_length'] = 15;
	}

	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	function get_raw_value( $post_id ) {
		$value = '';

		$p = get_post( $post_id );
		$extended = get_extended( $p->post_content );

		if ( ! empty( $extended['extended'] ) ) {
			$value = ac_helper()->string->trim_words( $extended['main'], $this->get_option( 'excerpt_length' ) );
		}

		return $value;
	}

	function display_settings() {
		$this->settings()->word_limit_field();
	}

}