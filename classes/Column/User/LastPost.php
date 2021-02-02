<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings;

/**
 * @since 4.2.6
 */
class LastPost extends Column {

	public function __construct() {
		$this->set_type( 'column-latest_post' );
		$this->set_label( __( 'Last Post', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$first_post_id = $this->get_raw_value( $id );

		if ( ! $first_post_id ) {
			return $this->get_empty_char();
		}

		$post = get_post( $first_post_id );

		return $this->get_formatted_value( $post->ID );
	}

	/**
	 * @return string
	 */
	protected function get_related_post_type() {
		return $this->get_setting( 'post_type' )->get_value();
	}

	public function get_raw_value( $user_id ) {
		$posts = get_posts( [
			'author'      => $user_id,
			'fields'      => 'ids',
			'number'      => 1,
			'post_status' => $this->get_related_post_stati(),
			'post_type'   => $this->get_related_post_type(),
		] );

		return empty( $posts ) ? null : $posts[0];
	}

	/**
	 * @return array
	 */
	public function get_related_post_stati() {
		return $this->get_setting( Settings\Column\PostStatus::NAME )->get_value();
	}

	protected function register_settings() {
		$this->add_setting( new Settings\Column\PostType( $this ) );
		$this->add_setting( new Settings\Column\PostStatus( $this ) );
		$this->add_setting( new Settings\Column\Post( $this ) );
	}

}