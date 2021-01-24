<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings;

/**
 * @since NEWVERSION
 */
class LatestPost extends Column {

	public function __construct() {
		$this->set_type( 'column-latest_post' );
		$this->set_label( __( 'Latest Post', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$first_post_id = $this->get_raw_value( $id );

		if ( ! $first_post_id ) {
			return $this->get_empty_char();
		}

		$post = get_post( $first_post_id );
		$value = sprintf( '%s', $this->get_formatted_value( $post->ID ) );

		if ( 'on' === $this->get_setting( 'display_date' )->get_value() ) {
			$value .= sprintf( ' - %s', $post->post_date );
		}

		return $value;
	}

	public function get_raw_value( $user_id ) {
		$posts = get_posts( [
			'author'    => $user_id,
			'fields'    => 'ids',
			'number'    => 1,
			'post_type' => $this->get_setting( 'post_type' )->get_value(),
		] );

		return empty( $posts ) ? null : $posts[0];
	}

	protected function register_settings() {
		$this->add_setting( new Settings\Column\PostType( $this ) );
		$this->add_setting( new Settings\Column\Post( $this ) );
		$this->add_setting( new Settings\Column\DisplayDate( $this ) );
	}

}