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

		$this->options['post_property_display'] = 'title';
		$this->options['post_link_to'] = 'edit_post';
	}

	public function get_value( $id ) {
		$raw_value = $this->get_raw_value( $id );

		$link = false;

		// Get page to link to
		switch ( $this->get_option( 'post_link_to' ) ) {
			case 'edit_post' :
				$link = get_edit_post_link( $raw_value );
				break;
			case 'view_post' :
				$link = get_permalink( $raw_value );
				break;
			case 'edit_author' :
				$link = get_edit_user_link( get_post_field( 'post_author', $raw_value ) );
				break;
			case 'view_author' :
				$link = get_author_posts_url( get_post_field( 'post_author', $raw_value ) );
				break;
		}

		// Get property of post to display
		switch ( $this->get_option( 'post_property_display' ) ) {
			case 'author' :
				$label = get_the_author_meta( 'display_name', get_post_field( 'post_author', $raw_value ) );
				break;
			case 'id' :
				$label = $raw_value;
				break;
			default:
				$label = get_the_title( $raw_value );
				break;
		}

		$value = $link ? "<a href='{$link}'>{$label}</a>" : $label;

		return $value;
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_post_ID;
	}

	public function display_settings() {
		$this->display_field_post_property_display();
		$this->display_field_post_link_to();
	}
}