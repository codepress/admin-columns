<?php

/**
 * @since 2.0
 */
class AC_ListScreen_Comment extends AC_ListScreenWP {

	public function __construct() {

		$this->set_label( __( 'Comments' ) );
		$this->set_singular_label( __( 'Comment' ) );
		$this->set_meta_type( 'comment' );
		$this->set_screen_base( 'edit-comments' );
		$this->set_key( 'wp-comments' );
		$this->set_screen_id( 'edit-comments' );
		$this->set_group( 'comment' );

		/* @see WP_Comments_List_Table */
		$this->set_list_table_class( 'WP_Comments_List_Table' );
	}

	public function get_column_headers() {
		$table = $this->get_list_table();

		// Since 4.4 the `floated_admin_avatar` filter is added in the constructor of the `WP_Comments_List_Table` class.
		// Here we remove the filter from the constructor.
		remove_filter( 'comment_author', array( $table, 'floated_admin_avatar' ), 10 );

		return (array) get_column_headers( $this->get_screen_id() );
	}

	public function set_manage_value_callback() {
		add_action( 'manage_comments_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	/**
	 * @since 3.5
	 */
	public function get_table_attr_id() {
		return '#the-comment-list';
	}

	/**
	 * @since NEWVERSION
	 * @return WP_Comment Comment
	 */
	protected function get_object_by_id( $comment_id ) {
		return get_comment( $comment_id );
	}

	/**
	 * @param string $column_name
	 * @param int $id
	 */
	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

}