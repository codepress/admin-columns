<?php

namespace AC;

class ListScreenGroupsFactory {

	public static function create(): Groups {
		$groups = new Groups();

		$groups->add( 'post', __( 'Post Type', 'codepress-admin-columns' ), 5 );
		$groups->add( 'user', __( 'Users' ) );
		$groups->add( 'media', __( 'Media' ) );
		$groups->add( 'comment', __( 'Comments' ) );
		$groups->add( 'link', __( 'Links' ), 15 );

		do_action( 'ac/list_screen_groups', $groups );

		return $groups;
	}

}