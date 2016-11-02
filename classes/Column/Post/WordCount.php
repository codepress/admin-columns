<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_WordCount extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-word_count' );
		$this->set_label( __( 'Word count', 'codepress-admin-columns' ) );
	}

	function get_value( $post_id ) {
		$count = $this->get_raw_value( $post_id );

		return $count ? $count : $this->get_empty_char();
	}

	function get_raw_value( $post_id ) {
		return ac_helper()->string->word_count( get_post_field( 'post_content', $post_id ) );
	}

	function is_valid() {
		return post_type_supports( $this->get_post_type(), 'editor' );
	}

}