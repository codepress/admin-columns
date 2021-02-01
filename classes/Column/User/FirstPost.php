<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings;

/**
 * @since 4.2.6
 */
class FirstPost extends Column {

	public function __construct() {
		$this->set_type( 'column-first_post' );
		$this->set_label( __( 'First Post', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$first_post_id = $this->get_raw_value( $id );

		if ( ! $first_post_id ) {
			return $this->get_empty_char();
		}

		$post = get_post( $first_post_id );

		return $this->get_formatted_value( $post->ID );
	}

	public function get_raw_value( $user_id ) {
		$posts = get_posts( [
			'author'      => $user_id,
			'fields'      => 'ids',
			'number'      => 1,
			'orderby'     => 'date',
			'post_status' => 'any',
			'order'       => 'ASC',
			'post_type'   => $this->get_related_post_type(),
		] );

		return empty( $posts ) ? null : $posts[0];
	}

	/**
	 * @return string
	 */
	protected function get_related_post_type() {
		return $this->get_setting( 'post_type' )->get_value();
	}

	protected function register_settings() {
		$this->add_setting( new Settings\Column\PostType( $this ) );
		$this->add_setting( new Settings\Column\Post( $this ) );
	}

}