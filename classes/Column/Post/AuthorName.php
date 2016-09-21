<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying information about the author of a post, such as the
 * author's display name, user ID and email address.
 *
 * @since 2.0
 */
class AC_Column_Post_AuthorName extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-author_name';
		$this->properties['label'] = __( 'Display Author As', 'codepress-admin-columns' );
	}

	public function get_value( $post_id ) {
		$author_id = $this->get_post_author( $post_id );

		// User name
		$user_name = $this->format->user( $author_id );

		// Link To
		if ( $link = $this->format->user_link_to( $author_id ) ) {
			$user_name = '<a href="' . esc_url( $link ) . '">' . esc_html( $user_name ) . '</a>';
		}

		return $user_name;
	}

	public function get_raw_value( $post_id ) {
		return $this->get_post_author( $post_id );
	}

	private function get_post_author( $post_id ) {
		return ac_helper()->post->get_raw_field( 'post_author', $post_id );
	}

	public function display_settings() {
		$this->field_settings->user();
		$this->field_settings->user_link_to();
	}

}