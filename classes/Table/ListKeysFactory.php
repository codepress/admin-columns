<?php
declare( strict_types=1 );

namespace AC\Table;

use AC\Type\ListKey;
use LogicException;

class ListKeysFactory implements ListKeysFactoryInterface {

	public function create(): ListKeyCollection {
		if ( ! did_action( 'init' ) ) {
			throw new LogicException( "Call after the `init` hook." );
		}

		$keys = new ListKeyCollection();

		foreach ( $this->get_post_types() as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );

			if ( $post_type_object ) {
				$keys->add( new ListKey( $post_type ) );
			}
		}

		$keys->add( new ListKey( 'wp-comments' ) );
		$keys->add( new ListKey( 'wp-users' ) );
		$keys->add( new ListKey( 'wp-media' ) );

		do_action( 'ac/list_keys', $keys );

		return $keys;
	}

	protected function get_post_types(): array {
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

		return apply_filters( 'ac/post_types', $post_types );
	}

	private function has_post( string $post_type ): bool {
		global $wpdb;

		return (bool) $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s LIMIT 1", $post_type ) );
	}

}