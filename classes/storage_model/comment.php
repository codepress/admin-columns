<?php

class CPAC_Storage_Model_Comment extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0
	 */
	function __construct() {

		$this->key 		 = 'wp-comments';
		$this->label 	 = __( 'Comments' );
		$this->type 	 = 'comment';
		$this->meta_type = 'comment';
		$this->page 	 = 'edit-comments';
		$this->menu_type = 'other';

		// headings
		add_filter( "manage_{$this->page}_columns",  array( $this, 'add_headings' ), 100 ); // Filter is located in get_column_headers().

		// values
		add_action( 'manage_comments_custom_column', array( $this, 'manage_value' ), 100, 2 );

		parent::__construct();
	}

	/**
	 * @since 2.3.4
	 * @see CPAC_Storage_Model::is_columns_screen()
	 */
	public function is_columns_screen() {

		$is_columns_screen = parent::is_columns_screen();

		if ( ! $is_columns_screen ) {
			if ( ! empty( $_REQUEST['_ajax_nonce-replyto-comment'] ) && wp_verify_nonce( $_REQUEST['_ajax_nonce-replyto-comment'], 'replyto-comment' ) ) {
				$is_columns_screen = true;
			}
		}

		return $is_columns_screen;
	}

	/**
	 * Get WP default supported admin columns per post type.
	 *
	 * @see CPAC_Type::get_default_columns()
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_default_columns() {

		if ( ! function_exists('_get_list_table') ) {
			return array();
		}

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		// get columns
		$table 		= _get_list_table( 'WP_Comments_List_Table', array( 'screen' => 'comments' ) );
		$columns 	= (array) $table->get_columns();

		return $columns;
	}

	/**
     * Get Meta
     *
	 * @since 2.0
	 *
	 * @return array
     */
    public function get_meta() {
        global $wpdb;

        if ( $cache = wp_cache_get( $this->key, 'cac_columns' ) ) {
        	$result = $cache;
        }
        else {
			$result = $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1", ARRAY_N );
			wp_cache_add( $this->key, $result, 'cac_columns', 10 ); // 10 sec.
		}
		return $result;
    }

	/**
	 * Manage value
	 *
	 * @since 2.0
	 *
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function manage_value( $column_name, $comment_id ) {

		$value = '';

		// get column instance
		if ( $column = $this->get_column_by_name( $column_name ) ) {
			$value = $column->get_value( $comment_id );
		}

		// filters
		$value = apply_filters( "cac/column/value", $value, $comment_id, $column, $this->key );
		$value = apply_filters( "cac/column/value/{$this->type}", $value, $comment_id, $column, $this->key );

		echo $value;
	}

}