<?php

if ( ! defined( 'ABSPATH' ) )  {
	exit;
}

/**
 * @since 2.0
 */
class AC_ListTableManager_Comment extends AC_ListTableManagerWPAbstract {

	public function __construct() {
		parent::__construct();

		$this->key = 'wp-comments';
		$this->label = __( 'Comments' );
		$this->singular_label = __( 'Comment' );
		$this->type = 'comment';
		$this->meta_type = 'comment';
		$this->base = 'edit-comments';
		$this->screen = 'edit-comments';
		$this->list_table = 'WP_Comments_List_Table';
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

	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1", ARRAY_N );
	}

	public function manage_value( $column_name, $id ) {
		echo $this->columns()->get_display_value_by_column_name( $column_name, $id );
	}

}