<?php
declare( strict_types=1 );

namespace AC\Admin\MenuListFactory;

use AC\Admin\MenuListFactory;
use AC\Admin\MenuListItems;
use AC\Admin\Type\MenuListItem;
use LogicException;

class MenuFactory implements MenuListFactory {

	public function create(): MenuListItems {
		if ( ! did_action( 'init' ) ) {
			throw new LogicException( "Call after the `init` hook." );
		}

		$menu = new MenuListItems();

		foreach ( $this->get_post_types() as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );

			if ( $post_type_object ) {
				$menu->add( new MenuListItem( $post_type, $post_type_object->label, admin_url(), 'post' ) );
			}
		}

		$menu->add( new MenuListItem( 'wp-comments', __( 'Comments', 'codepress-admin-columns' ), admin_url(), 'comment' ) );
		$menu->add( new MenuListItem( 'wp-users', __( 'Users', 'codepress-admin-columns' ), admin_url(), 'user' ) );
		$menu->add( new MenuListItem( 'wp-media', __( 'Media', 'codepress-admin-columns' ), admin_url(), 'media' ) );

		do_action( 'ac/admin/menu_list', $menu );

		return $menu;
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
		$wp_block = 'post';

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