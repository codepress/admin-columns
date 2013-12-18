<?php

class CPAC_Storage_Model_Post extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	function __construct( $post_type ) {

		$this->key 		 = $post_type;
		$this->label 	 = $this->get_label();
		$this->type 	 = 'post';
		$this->page 	 = 'edit';
		$this->post_type = $post_type;

		// @todo_minor
		// Add parent::__construct and move these two over:
		// $this->set_custom_columns()
		// add_action( 'admin_init', array( $this, 'set_columns' ) );
		// also for the other types

		$this->set_columns_filepath();

		// Populate columns variable.
		// This is used for manage_value. By storing these columns we greatly improve performance.
		add_action( 'admin_init', array( $this, 'set_columns' ) );

		// Headings

		// Since 3.1
		add_filter( "manage_{$post_type}_posts_columns", array( $this, 'add_headings' ), 10, 1 );

		// Deprecated ( as of 3.1 ) Note: This one is still used by woocommerce.
		// @todo_minor check compatibility issues for this deprecated filter
		add_filter( "manage_{$this->page}-{$post_type}_columns",  array( $this, 'add_headings' ), 10, 1 );

		// values
		add_action( "manage_{$post_type}_posts_custom_column", array( $this, 'manage_value' ), 10, 2 );
	}

	/**
	 * Get screen link
	 *
	 * @since 2.0.0
	 *
	 * @return string Link
	 */
	protected function get_screen_link() {

		return add_query_arg( array( 'post_type' => $this->key ), admin_url( $this->page . '.php' ) );
	}

	/**
	 * Get Label
	 *
	 * @since 2.0.0
	 *
	 * @return string Singular posttype name
	 */
	private function get_label() {
		$posttype_obj = get_post_type_object( $this->key );

		return $posttype_obj->labels->name;
	}

	/**
	 * Get WP default supported admin columns per post type.
	 *
	 * @see CPAC_Type::get_default_columns()
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_default_columns() {

		if ( ! function_exists('_get_list_table') ) return array();

		//if ( ! $this->is_columns_screen() && ! $this->is_settings_page() )
			//return array();

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cac/columns/default/posts" );
		do_action( "cac/columns/default/storage_key={$this->key}" );

        // Get the WP default columns
		$table 	 = _get_list_table( 'WP_Posts_List_Table', array( 'screen' => $this->key ) );
        $columns = $table->get_columns();

        // Get columns that have been set by other plugins. If a plugin use the hook "manage_edit-{$post_type}_columns"
		// we know that the columns have been overwritten. Use these columns instead of the WP default ones.
        //
		// We have to make sure this filter only loads on the Admin Columns settings page. To prevent a loop
		// when it's being called by CPAC_Storage_Model::add_headings()
		if ( $this->is_settings_page() )
			$columns =  array_merge( get_column_headers( 'edit-' . $this->key ), $columns );

		return array_filter( $columns );
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

		return $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $this->key ), ARRAY_N );
    }

	/**
	 * Manage value
	 *
	 * @since 2.0.0
	 *
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function manage_value( $column_name, $post_id ) {

		$value = '';

		$column = $this->get_column_by_name( $column_name );

		// get value
		if ( $column )
			$value = $column->get_value( $post_id );

		// Filters
		$value = apply_filters( "cac/column/value", $value, $post_id, $column, $this->key );
		$value = apply_filters( "cac/column/value/{$this->type}", $value, $post_id, $column, $this->key );

		echo $value;
	}
}