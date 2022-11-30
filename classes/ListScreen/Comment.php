<?php

namespace AC\ListScreen;

use AC;
use AC\Column;
use AC\WpListTableFactory;
use WP_Comment;
use WP_Comments_List_Table;

class Comment extends AC\ListScreenWP {

	public function __construct() {

		$this->set_label( __( 'Comments' ) )
		     ->set_singular_label( __( 'Comment' ) )
		     ->set_meta_type( 'comment' )
		     ->set_screen_base( 'edit-comments' )
		     ->set_key( 'wp-comments' )
		     ->set_screen_id( 'edit-comments' )
		     ->set_group( 'comment' );
	}

	/**
	 * @param int $id
	 *
	 * @return WP_Comment
	 */
	protected function get_object( $id ) {
		return get_comment( $id );
	}

	/**
	 * @return WP_Comments_List_Table
	 */
	protected function get_list_table() {
		return ( new WpListTableFactory() )->create_comment_table( $this->get_screen_id() );
	}

	public function set_manage_value_callback() {
		add_action( 'manage_comments_custom_column', [ $this, 'manage_value' ], 100, 2 );
	}

	/**
	 * @since 3.5
	 */
	public function get_table_attr_id() {
		return '#the-comment-list';
	}

	/**
	 * @param string $column_name
	 * @param int    $id
	 */
	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

	/**
	 * Register column types
	 */
	protected function register_column_types() {
		$this->register_column_types_from_list( [
			AC\Column\CustomField::class,
			AC\Column\Actions::class,
			Column\Comment\Agent::class,
			Column\Comment\Approved::class,
			Column\Comment\Author::class,
			Column\Comment\AuthorAvatar::class,
			Column\Comment\AuthorEmail::class,
			Column\Comment\AuthorIP::class,
			Column\Comment\AuthorName::class,
			Column\Comment\AuthorUrl::class,
			Column\Comment\Comment::class,
			Column\Comment\Date::class,
			Column\Comment\DateGmt::class,
			Column\Comment\Excerpt::class,
			Column\Comment\ID::class,
			Column\Comment\Post::class,
			Column\Comment\ReplyTo::class,
			Column\Comment\Response::class,
			Column\Comment\Status::class,
			Column\Comment\Type::class,
			Column\Comment\User::class,
			Column\Comment\WordCount::class,
		] );
	}

}