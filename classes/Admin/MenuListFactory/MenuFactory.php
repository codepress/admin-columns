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

		if ( is_network_admin() ) {
			return new MenuListItems();
		}

		$items = [];

		foreach ( $this->get_post_types() as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );

			if ( $post_type_object ) {
				$items[] = new MenuListItem( $post_type, $post_type_object->label, admin_url(), __( 'Post Type', 'codepress-admin-columns' ) );
			}
		}

		$items[] = new MenuListItem( 'wp-comments', __( 'Comments', 'codepress-admin-columns' ), admin_url(), __( 'Comments', 'codepress-admin-columns' ) );
		$items[] = new MenuListItem( 'wp-users', __( 'Users', 'codepress-admin-columns' ), admin_url(), __( 'Users', 'codepress-admin-columns' ) );
		$items[] = new MenuListItem( 'wp-media', __( 'Media', 'codepress-admin-columns' ), admin_url(), __( 'Media', 'codepress-admin-columns' ) );

		return new MenuListItems( $items );
	}

	protected function get_post_types(): array {
		$post_types = get_post_types( [
			'_builtin' => false,
			'show_ui'  => true,
		] );

		foreach ( [ 'post', 'page', 'wp_block' ] as $builtin ) {
			if ( post_type_exists( $builtin ) ) {
				$post_types[ $builtin ] = $builtin;
			}
		}

		return apply_filters( 'ac/post_types', $post_types );
	}

}