<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4.7
 */
class AC_Column_Comment_Post extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-post';
		$this->properties['label'] = __( 'Post', 'codepress-admin-columns' );

		$this->default_options['post_property_display'] = 'title';
		$this->default_options['post_link_to'] = 'edit_post';
	}

	public function get_value( $id ) {
		$post_id = $this->get_raw_value( $id );

		$label = esc_html( $this->format->post( $post_id ) );

		// Get page to link to
		if ( $link = $this->format->post_link_to( $post_id ) ) {
			$label = '<a href="' . esc_url( $link ) . '">' . $label . '</a>';
		}

		return $label;
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_post_ID;
	}

	public function display_settings() {
		$this->field_settings->post();
		$this->field_settings->post_link_to();
	}

}