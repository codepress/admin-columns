<?php

namespace AC;

class ListScreens implements Registerable {

	public function register() {
		add_action( 'init', [ $this, 'register_list_screens' ], 1000 ); // run after all post types are registered
	}

	public function register_list_screens() {
		$list_screens = [];

		foreach ( $this->get_post_types() as $post_type ) {
			$list_screens[] = new ListScreen\Post( $post_type );
		}

		$list_screens[] = new ListScreen\Media();
		$list_screens[] = new ListScreen\Comment();

		if ( ! is_multisite() ) {
			$list_screens[] = new ListScreen\User();
		}

		foreach ( $list_screens as $list_screen ) {
			$this->register_list_screen( $list_screen );
		}

		do_action( 'ac/list_screens', $this );
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return self
	 */
	public function register_list_screen( ListScreen $list_screen ) {
		ListScreenTypes::instance()->register_list_screen( $list_screen );

		return $this;
	}

	/**
	 * Get a list of post types for which Admin Columns is active
	 * @return array List of post type keys (e.g. post, page)
	 * @since 1.0
	 */
	public function get_post_types() {
		$post_types = get_post_types( [
			'_builtin' => false,
			'show_ui'  => true,
		] );

		foreach ( [ 'post', 'page', 'wp_block' ] as $builtin ) {
			if ( post_type_exists( $builtin ) ) {
				$post_types[ $builtin ] = $builtin;
			}
		}

		/**
		 * Filter the post types for which Admin Columns is active
		 *
		 * @param array $post_types List of active post type names
		 *
		 * @since 2.0
		 */
		return apply_filters( 'ac/post_types', $post_types );
	}

}