<?php
declare( strict_types=1 );

namespace AC;

use AC\ApplyFilter\PostTypes;
use LogicException;

class PostTypeRepository {

	public function exists( string $post_type ): bool {
		static $post_types;

		if ( null === $post_types ) {
			$post_types = $this->find_all();
		}

		return in_array( $post_type, $post_types, true );
	}

	public function find_all(): array {
		if ( ! did_action( 'init' ) ) {
			throw new LogicException( "Call after the `init` hook." );
		}

		$post_types = get_post_types( [
			'_builtin' => false,
			'show_ui'  => true,
		] );

		foreach ( [ 'post', 'page' ] as $builtin ) {
			if ( post_type_exists( $builtin ) ) {
				$post_types[ $builtin ] = $builtin;
			}
		}

		// Reusable content blocks for Gutenberg
		$wp_block = 'wp_block';

		if ( post_type_exists( $wp_block ) && $this->has_post( $wp_block ) ) {
			$post_types[ $wp_block ] = $wp_block;
		}

		return ( new PostTypes() )->apply_filters( $post_types );
	}

	private function has_post( string $post_type ): bool {
		global $wpdb;

		// TODO do we want a DB call here?
		return (bool) $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s LIMIT 1", $post_type ) );
	}

}