<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Post_Word_Count
 *
 * @since 2.0
 */
class CPAC_Column_Post_Word_Count extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-word_count';
		$this->properties['label'] = __( 'Word count', 'codepress-admin-columns' );
	}

	function get_value( $post_id ) {
		$count = $this->get_raw_value( $post_id );

		return $count ? $count : $this->get_empty_char();
	}

	function get_raw_value( $post_id ) {
		return ac_helper()->string->word_count( get_post_field( 'post_content', $post_id ) );
	}

	function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'editor' );
	}

}