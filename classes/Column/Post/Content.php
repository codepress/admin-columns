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
		return $this->format->word_limit( $this->get_raw_value( $post_id ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_content', $post_id, 'raw' );
	}

	public function display_settings() {
		$this->field_settings->word_limit();
	}

}