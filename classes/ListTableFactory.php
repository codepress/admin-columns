<?php

namespace AC;

use AC\ListScreen\Comment;
use AC\ListScreen\Media;
use AC\ListScreen\Post;
use AC\ListScreen\User;
use ACP\ListScreen\Taxonomy;
use WP_Comments_List_Table;
use WP_List_Table;
use WP_Media_List_Table;
use WP_Posts_List_Table;
use WP_Terms_List_Table;
use WP_Users_List_Table;

class ListTableFactory {

	/**
	 * @param string $table
	 * @param string $screen_id
	 *
	 * @return WP_List_Table|null
	 */
	public function create( $table, $screen_id ) {

		switch ( $table ) {
			case 'post' :
				require_once( ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php' );

				return new WP_Posts_List_Table( [ 'screen' => $screen_id ] );

			case 'media' :
				require_once( ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php' );

				return new WP_Media_List_Table( [ 'screen' => $screen_id ] );

			case 'user' :
				require_once( ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php' );

				return new WP_Users_List_Table( [ 'screen' => $screen_id ] );

			case 'comment' :
				require_once( ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php' );

				$table = new WP_Comments_List_Table( [ 'screen' => $screen_id ] );

				// Since 4.4 the `floated_admin_avatar` filter is added in the constructor of the `\WP_Comments_List_Table` class.
				remove_filter( 'comment_author', [ $table, 'floated_admin_avatar' ], 10 );

				return $table;

			case 'taxonomy' :
				require_once( ABSPATH . 'wp-admin/includes/class-wp-terms-list-table.php' );

				return new WP_Terms_List_Table( [ 'screen' => $screen_id ] );

			// todo: network tables
		}

		return null;
	}

	public function create_by_list_screen( ListScreen $list_screen ) {
		switch ( true ) {
			case $list_screen instanceof Post :
				return $this->create( 'post', $list_screen->get_screen_id() );
			case $list_screen instanceof Comment :
				return $this->create( 'comment', $list_screen->get_screen_id() );
			case $list_screen instanceof User :
				return $this->create( 'user', $list_screen->get_screen_id() );
			case $list_screen instanceof Taxonomy :
				return $this->create( 'taxonomy', $list_screen->get_screen_id() );
			case $list_screen instanceof Media :
				return $this->create( 'media', $list_screen->get_screen_id() );

				// todo: network tables
		}
	}

}