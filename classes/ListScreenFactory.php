<?php

namespace AC;

use AC\ListScreen\Comment;
use AC\ListScreen\Media;
use AC\ListScreen\Post;
use AC\ListScreen\User;
use AC\Type\ListScreenId;

class ListScreenFactory {

	/**
	 * @param string       $key
	 * @param ListScreenId $id
	 *
	 * @return Comment|Media|Post|User
	 */
	public function create( $key, ListScreenId $id = null ) {
		switch ( $key ) {
			case 'wp-users' :
				return ( new User() )->set_layout_id( $id->get_id() );
			case 'wp-media' :
				return ( new Media() )->set_layout_id( $id->get_id() );
			case 'wp-comments' :
				return ( new Comment() )->set_layout_id( $id->get_id() );
			default :
				$list_screen = ( new Post( $key ) )->set_layout_id( $id->get_id() );

				return $list_screen;
		}
	}

}