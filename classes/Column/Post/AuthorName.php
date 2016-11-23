<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying information about the author of a post, such as the
 * author's display name, user ID and email address.
 *
 * @since 2.0
 */
class AC_Column_Post_AuthorName extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-author_name' );
		$this->set_label( __( 'Display Author As', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		$user_id = $this->get_post_author( $post_id );

		// TODO: this works!
		return $this->get_setting( 'user' )->format( $user_id );

		//return ac_helper()->html->link( $this->format->user_link_to( $author_id ), $this->format->user( $author_id ) );
	}

	public function get_raw_value( $post_id ) {
		return $this->get_post_author( $post_id );
	}

	private function get_post_author( $post_id ) {
		return ac_helper()->post->get_raw_field( 'post_author', $post_id );
	}

	public function register_settings() {
		parent::register_settings();

		$this->add_setting( new AC_Settings_Setting_User( $this ) );
	}

}