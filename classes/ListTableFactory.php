<?php

namespace AC;

use WP_Comments_List_Table;
use WP_Media_List_Table;
use WP_MS_Sites_List_Table;
use WP_MS_Users_List_Table;
use WP_Posts_List_Table;
use WP_Terms_List_Table;
use WP_Users_List_Table;

class ListTableFactory {

	/**
	 * @param string $screen_id
	 *
	 * @return WP_Posts_List_Table
	 */
	public function create_post_table( $screen_id ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php' );

		return new WP_Posts_List_Table( [ 'screen' => $screen_id ] );
	}

	/**
	 * @param string $screen_id
	 *
	 * @return WP_Users_List_Table
	 */
	public function create_user_table( $screen_id ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php' );

		return new WP_Users_List_Table( [ 'screen' => $screen_id ] );
	}

	/**
	 * @param string $screen_id
	 *
	 * @return WP_Comments_List_Table
	 */
	public function create_comment_table( $screen_id ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php' );

		$table = new WP_Comments_List_Table( [ 'screen' => $screen_id ] );

		// Since 4.4 the `floated_admin_avatar` filter is added in the constructor of the `\WP_Comments_List_Table` class.
		remove_filter( 'comment_author', [ $table, 'floated_admin_avatar' ], 10 );

		return $table;
	}

	/**
	 * @param string $screen_id
	 *
	 * @return WP_Media_List_Table
	 */
	public function create_media_table( $screen_id ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php' );

		return new WP_Media_List_Table( [ 'screen' => $screen_id ] );
	}

	/**
	 * @param string $screen_id
	 *
	 * @return WP_Terms_List_Table
	 */
	public function create_taxonomy_table( $screen_id ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-terms-list-table.php' );

		return new WP_Terms_List_Table( [ 'screen' => $screen_id ] );
	}

	/**
	 * @param string $screen_id
	 *
	 * @return WP_MS_Users_List_Table
	 */
	public function create_network_user_table( $screen_id ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-ms-users-list-table.php' );

		return new WP_MS_Users_List_Table( [ 'screen' => $screen_id ] );
	}

	/**
	 * @param string $screen_id
	 *
	 * @return WP_MS_Sites_List_Table
	 */
	public function create_network_site_table( $screen_id ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-ms-sites-list-table.php' );

		return new WP_MS_Sites_List_Table( [ 'screen' => $screen_id ] );
	}

}