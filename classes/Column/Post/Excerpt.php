<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Excerpt extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-excerpt';
		$this->properties['label'] = __( 'Excerpt', 'codepress-admin-columns' );
	}

	public function get_value( $post_id ) {
		$value = $this->get_formatted_value( $post_id );
		if ( ! has_excerpt( $post_id ) && $value ) {
			$value = '<span class="cpac-inline-info">' . __( 'Excerpt from content', 'codepress-admin-columns' ) . '</span> ' . $value;
		}

		return $value;
	}

	public function get_raw_value( $post_id ) {
		return ac_helper()->post->get_raw_field( 'post_excerpt', $post_id );
	}

	public function get_formatted_value( $post_id ) {
		return $this->format->word_limit( ac_helper()->post->excerpt( $post_id ) );
	}

	public function display_settings() {
		$this->field_settings->word_limit();
	}

}