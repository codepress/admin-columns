<?php

class CPAC_Storage_Model_Post extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	function __construct( $post_type ) {

		$this->key 		= $post_type;
		$this->label 	= $this->get_label();
		$this->type 	= 'post';
		$this->page 	= 'edit';

		// headings
		add_filter( "manage_{$this->page}-{$post_type}_columns",  array( $this, 'add_headings' ) );

		// values
		add_action( "manage_{$post_type}_posts_custom_column", array( $this, 'manage_value' ), 10, 2 );
	}

	/**
	 * Get Label
	 *
	 * @since 2.0.0.0
	 *
	 * @return string Singular posttype name
	 */
	private function get_label() {
		$posttype_obj = get_post_type_object( $this->key );

		return $posttype_obj->labels->singular_name;
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
		global $pagenow;

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cpac_before_default_columns_posts", $this );
		do_action( "cpac_before_default_columns_{$this->key}", $this );

		// Get columns that have been set by other plugins. If a plugin use the hook "manage_edit-{$post_type}_columns"
		// we know that the columns have been overwritten. Use these columns instead of the WP default ones.
		//
		// We have to make sure this filter only loads on the Admin Columns settings page. To prevent a loop
		// when it's being called by CPAC_Storage_Model::add_headings()
		$columns = 'admin.php' == $pagenow ? get_column_headers( "edit-{$this->key}" ) : '';

		// Get the WP default columns if there is no 3rd party plugin trying to set columns using the hook above.
		if ( ! $columns ) {

			$table 		= _get_list_table( 'WP_Posts_List_Table', array( 'screen' => $this->key ) );
			$columns 	= $table->get_columns();
		}

		$columns = apply_filters( 'cpac_default_columns_posts', $columns, $this );
		$columns = apply_filters( "cpac_default_columns_{$this->key}", $columns, $this );

		return $columns;
	}

	/**
     * Get Meta Keys
     *
	 * @since 2.0.0.0
	 *
	 * @return array
     */
    public function get_meta_keys() {
        global $wpdb;

		$fields = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $this->key ), ARRAY_N );

		if ( is_wp_error( $fields ) )
			$fields = false;

		return apply_filters( "cpac_get_meta_keys_{$this->key}", $this->maybe_add_hidden_meta( $fields ), $this );
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

		// get column instance
		if ( $column = $this->get_column_by_name( $column_name ) ) {
			$value = $column->get_value( $post_id );
		}

		$value = apply_filters( "cpac_value_posts", $value, $column );
		$value = apply_filters( "cpac_value_{$this->key}", $value, $column );

		echo $value;
	}
}