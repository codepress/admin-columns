<?php

class CPAC_Storage_Model_Media extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	function __construct() {

		$this->key 		= 'wp-media';
		$this->label 	= __( 'Media Library' );
		$this->type 	= 'media';
		$this->page 	= 'upload';

		$this->set_custom_columns();

		// Populate columns variable.
		// This is used for manage_value. By storing these columns we greatly improve performance.
		add_action( 'admin_init', array( $this, 'set_columns' ) );

		// headings
		add_filter( "manage_{$this->page}_columns",  array( $this, 'add_headings' ) );

		// values
		add_action( 'manage_media_custom_column', array( $this, 'manage_value' ), 10, 2 );
	}

	/**
	 * Get WP default supported admin columns per post type.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_default_columns() {

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		// get columns
		$table 		= _get_list_table( 'WP_Media_List_Table', array( 'screen' => 'upload' ) );
		$columns 	= $table->get_columns();

		return $columns;
	}

	/**
     * Get Meta
     *
	 * @since 2.0.0
	 *
	 * @return array
     */
    public function get_meta() {
        global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = 'attachment' ORDER BY 1", ARRAY_N );
    }

	/**
	 * Manage value
	 *
	 * @since 2.0.0
	 *
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function manage_value( $column_name, $media_id ) {

		$value = '';

		// get column instance
		if ( $column = $this->get_column_by_name( $column_name ) ) {
			$value = $column->get_value( $media_id );
		}

		// add hook
		echo apply_filters( "cac/column/value/type={$this->key}", $value, $column );
	}

}