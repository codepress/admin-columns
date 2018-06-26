<?php

namespace AC;

class ListScreenGroups {

	/**
	 * @return Groups
	 */
	public static function get_groups() {
		$groups = new Groups();

		$groups->register_group( 'post', __( 'Post Type', 'codepress-admin-columns' ), 5 );
		$groups->register_group( 'user', __( 'Users' ) );
		$groups->register_group( 'media', __( 'Media' ) );
		$groups->register_group( 'comment', __( 'Comments' ) );
		$groups->register_group( 'link', __( 'Links' ), 15 );

		do_action( 'ac/list_screen_groups', $groups );

		return $groups;
	}

}