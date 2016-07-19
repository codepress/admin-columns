<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4
 */
class AC_Column_Post_Content extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-content';
		$this->properties['label'] = __( 'Content', 'codepress-admin-columns' );
		$this->properties['object_property'] = 'post_content';

		$this->options['excerpt_length'] = 30;
	}

	public function get_value( $post_id ) {
		return ac_helper()->format->word_limit( $this->get_raw_value( $post_id ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_content', $post_id, 'raw' );
	}

	public function display_settings() {
		$this->field_settings->word_limit();
	}

}