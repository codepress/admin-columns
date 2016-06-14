<?php

if ( ! defined( 'ABSPATH' ) )  {
	exit; // exit if accessed directly
}

/**
 * @since 2.0
 */
class CPAC_Storage_Model_Comment extends CPAC_Storage_Model {

	public function __construct() {

		$this->key = 'wp-comments';
		$this->label = __( 'Comments' );
		$this->singular_label = __( 'Comment' );
		$this->type = 'comment';
		$this->meta_type = 'comment';
		$this->page = 'edit-comments';
		$this->table_classname = 'WP_Comments_List_Table';

		parent::__construct();
	}

	/**
	 * @since NEWVERSION
	 * @return WP_Comment Comment
	 */
	protected function get_object_by_id( $id ) {
		return get_comment( $id );
	}

	/**
	 * @since 2.4.9
	 */
	public function init_column_values() {
		add_action( 'manage_comments_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	public function get_default_column_names() {
		return array( 'cb', 'author', 'comment', 'response', 'date' );
	}

	protected function get_default_column_widths() {
		return array(
			'author'   => array( 'width' => 20 ),
			'response' => array( 'width' => 15 ),
			'date'     => array( 'width' => 14 ),
		);
	}

	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1", ARRAY_N );
	}

	public function manage_value( $column_name, $comment_id ) {
		echo $this->get_manage_value( $column_name, $comment_id );
	}
}
