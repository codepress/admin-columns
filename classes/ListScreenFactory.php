<?php

class AC_ListScreenFactory {

	/**
	 * @var AC_ListScreen[]
	 */
	static $types;

	/**
	 * Get registered list screens
	 *
	 * @since 3.0
	 * @return AC_ListScreen[]
	 */
	public static function get_types() {
		$list_screens = array();

		// Post types
		foreach ( self::get_post_types() as $post_type ) {
			$list_screens[] = new AC_ListScreen_Post( $post_type );
		}

		$list_screens[] = new AC_ListScreen_Media();
		$list_screens[] = new AC_ListScreen_Comment();

		// Users, not for network users
		if ( ! is_multisite() ) {
			$list_screens[] = new AC_ListScreen_User();
		}

		foreach ( $list_screens as $list_screen ) {
			self::register_list_screen( $list_screen );
		}

		/**
		 * Register list screens
		 *
		 * @since 3.0
		 *
		 * @param CPAC $this
		 */
		do_action( 'ac/list_screens', AC() );

		return self::$types;
	}

	/**
	 * @param AC_ListScreen $list_screen
	 */
	public static function register_list_screen( AC_ListScreen $list_screen ) {
		self::$types[ $list_screen->get_key() ] = $list_screen;
	}

	/**
	 * @param string $type
	 * @param int    $id Optional (layout) ID
	 *
	 * @return AC_ListScreen|false
	 */
	public static function get_list_screen( $type, $id = null ) {
		$types = self::get_types();

		if ( ! isset( $types[ $type ] ) ) {
			return false;
		}

		$list_screen = clone $types[ $type ];

		$list_screen->set_layout_id( $id );

		return $list_screen;
	}

	/**
	 * Get a list of post types for which Admin Columns is active
	 *
	 * @since 1.0
	 *
	 * @return array List of post type keys (e.g. post, page)
	 */
	public static function get_post_types() {
		$post_types = array();

		if ( post_type_exists( 'post' ) ) {
			$post_types['post'] = 'post';
		}
		if ( post_type_exists( 'page' ) ) {
			$post_types['page'] = 'page';
		}

		$post_types = array_merge( $post_types, get_post_types( array(
			'_builtin' => false,
			'show_ui'  => true,
		) ) );

		/**
		 * Filter the post types for which Admin Columns is active
		 *
		 * @since 2.0
		 *
		 * @param array $post_types List of active post type names
		 */
		return apply_filters( 'ac/post_types', $post_types );
	}

	/**
	 * @return AC_Groups
	 */
	public static function groups() {
		$groups = new AC_Groups();

		$groups->register_group( 'post', __( 'Post Type', 'codepress-admin-columns' ), 5 );
		$groups->register_group( 'user', __( 'Users' ) );
		$groups->register_group( 'media', __( 'Media' ) );
		$groups->register_group( 'comment', __( 'Comments' ) );
		$groups->register_group( 'link', __( 'Links' ), 15 );

		do_action( 'ac/list_screen_groups', $groups );

		return $groups;
	}

}