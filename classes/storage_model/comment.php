<?php

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

		parent::__construct();
	}

	/**
	 * @since 2.4.9
	 */
	public function init_manage_columns() {

		add_filter( "manage_{$this->page}_columns", array( $this, 'add_headings' ), 100 );
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

	public function get_default_columns() {
		if ( ! function_exists( '_get_list_table' ) ) {
			return array();
		}

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		// get columns
		$table = _get_list_table( 'WP_Comments_List_Table', array( 'screen' => 'comments' ) );

		// Since 4.4 the `floated_admin_avatar` filter is added in the constructor of the `WP_Comments_List_Table` class.
		// Here we remove the filter from the constructor.
		remove_filter( 'comment_author', array( $table, 'floated_admin_avatar' ), 10, 2 );

		$columns = (array) $table->get_columns();

		return $columns;
	}

	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1", ARRAY_N );
	}

	public function manage_value( $column_name, $comment_id ) {
		if ( ! ( $column = $this->get_column_by_name( $column_name ) ) ) {
			return false;
		}
		$value = $column->get_display_value( $comment_id );

		// hook
		$value = apply_filters( "cac/column/value", $value, $comment_id, $column, $this->key );
		$value = apply_filters( "cac/column/value/{$this->type}", $value, $comment_id, $column, $this->key );

		echo $value;
	}
}
