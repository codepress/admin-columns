<?php

namespace AC;

use AC\ListTable\Comment;
use AC\ListTable\Media;
use AC\ListTable\MsUser;
use AC\ListTable\Post;
use AC\ListTable\Taxonomy;
use AC\ListTable\User;
use WP_Comments_List_Table;
use WP_Media_List_Table;
use WP_MS_Users_List_Table;
use WP_Posts_List_Table;
use WP_Terms_List_Table;
use WP_Users_List_Table;

class ListTableFactory {

	/**
	 * @var WpListTableFactory
	 */
	private $wp_list_table_factory;

	public function __construct( WpListTableFactory $wp_list_table_factory ) {
		$this->wp_list_table_factory = $wp_list_table_factory;
	}

	public function create_by_globals() {
		global $wp_list_table, $current_screen;

		switch ( true ) {
			case $wp_list_table instanceof WP_Posts_List_Table :
				return new Post( $wp_list_table );

			case $wp_list_table instanceof WP_Users_List_Table :
				return new User( $wp_list_table );

			case $wp_list_table instanceof WP_Comments_List_Table :
				return new Comment( $wp_list_table );

			case $wp_list_table instanceof WP_Media_List_Table :
				return new Media( $wp_list_table );

			case $wp_list_table instanceof WP_Terms_List_Table :
				if ( ! $current_screen ) {
					return null;
				}

				return new Taxonomy( $wp_list_table, $current_screen->taxonomy );

			case $wp_list_table instanceof WP_MS_Users_List_Table :
				return new MsUser( $wp_list_table );
		}

		return null;

	}

	public function create_user_table( $screen_id ) {
		return new User( $this->wp_list_table_factory->create_user_table( $screen_id ) );
	}

	public function create_post_table( $screen_id ) {
		return new Post( $this->wp_list_table_factory->create_post_table( $screen_id ) );
	}

	public function create_comment_table( $screen_id ) {
		return new Comment( $this->wp_list_table_factory->create_comment_table( $screen_id ) );
	}

	public function create_media_table( $screen_id ) {
		return new Media( $this->wp_list_table_factory->create_media_table( $screen_id ) );
	}

	public function create_network_user_table( $screen_id ) {
		return new MsUser( $this->wp_list_table_factory->create_network_user_table( $screen_id ) );
	}

	public function create_taxonomy_table( $screen_id, $taxonomy ) {
		return new Taxonomy( $this->wp_list_table_factory->create_taxonomy_table( $screen_id ), $taxonomy );
	}

}