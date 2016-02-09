<?php

class CPAC_Storage_Model_Post extends CPAC_Storage_Model {

	public $post_type;

	/**
	 * @since 2.0
	 */
	public function __construct( $post_type ) {

		$this->key = $post_type;
		$this->post_type = $post_type;
		$this->type = 'post';
		$this->meta_type = 'post';
		$this->page = 'edit';
		$this->menu_type = 'post';

		$this->set_labels();

		parent::__construct();
	}

	/**
	 * @since 2.4.9
	 */
	public function init_manage_columns() {

		// Headings

		// Since 3.1
		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'add_headings' ), 100 );

		// Deprecated ( as of 3.1 ) Note: This one is still used by woocommerce.
		// Priority set to 100 top make sure the WooCommerce headings are overwritten by CAC
		// Filter is located in get_column_headers().
		// @todo_minor check compatibility issues for this deprecated filter
		add_filter( "manage_{$this->page}-{$this->post_type}_columns", array( $this, 'add_headings' ), 100 );

		// values
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'manage_value_callback' ), 100, 2 );
	}

	/**
	 * @since 2.3.5
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * @since 2.4.7
	 */
	public function get_posts( $args = array() ) {
		$defaults = array(
			'numberposts'   => - 1,
			'post_status'   => array( 'any', 'trash' ),
			'post_type'     => $this->post_type,
			'fields'        => 'ids',
			'no_found_rows' => 1, // lowers our carbon footprint
		);

		return (array) get_posts( array_merge( $defaults, $args ) );
	}

	/**
	 * @since 2.7.2
	 */
	private function set_labels() {
		$post_type_object = get_post_type_object( $this->post_type );
		$this->label = $post_type_object->labels->name;
		$this->singular_label = $post_type_object->labels->singular_name;
	}

	/**
	 * @since 2.2.1
	 */
	public function get_original_column_value( $column, $id ) {

		global $post;

		// Setup post data for current post
		$post_old = $post;
		$post = get_post( $id );
		setup_postdata( $post );

		// Remove Admin Columns action for this column's value
		remove_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'manage_value_callback' ), 100, 2 );

		ob_start();
		// Run WordPress native actions to display column content
		if ( is_post_type_hierarchical( $this->post_type ) ) {
			do_action( 'manage_pages_custom_column', $column, $id );
		}
		else {
			do_action( 'manage_posts_custom_column', $column, $id );
		}

		do_action( "manage_{$this->post_type}_posts_custom_column", $column, $id );

		$contents = ob_get_clean();

		// Add removed Admin Columns action for this column's value
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'manage_value_callback' ), 100, 2 );

		// Restore original post object
		$post = $post_old;

		if ( $post ) {
			setup_postdata( $post );
		}

		return $contents;
	}

	/**
	 * @since 2.4.4
	 */
	public function get_default_column_names() {

		$defaults = array( 'date' );

		if ( post_type_supports( $this->post_type, 'title' ) ) {
			$defaults[] = 'title';
		}
		if ( post_type_supports( $this->post_type, 'comments' ) ) {
			$defaults[] = 'comments';
		}

		if ( in_array( $this->post_type, array( 'post', 'page' ) ) ) {
			$defaults[] = 'cb';
			$defaults[] = 'author';
			$defaults[] = 'categories';
			$defaults[] = 'comments';
			$defaults[] = 'parent';
			$defaults[] = 'tags';
		}

		return $defaults;
	}

	/**
	 * @since 2.0
	 */
	protected function get_screen_link() {
		return add_query_arg( array( 'post_type' => $this->key ), admin_url( $this->page . '.php' ) );
	}

	/**
	 * @since 2.2
	 */
	public function is_current_screen() {
		$post_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'post';

		return ( $this->post_type === $post_type ) && parent::is_current_screen();
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

		if ( ! function_exists( '_get_list_table' ) ) {
			return array();
		}

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cac/columns/default/posts" );
		do_action( "cac/columns/default/storage_key={$this->key}" );
		do_action( "cac/columns/default/post_type={$this->post_type}" );

		// Initialize table so it can add actions to manage_{screenid}_columns
		_get_list_table( 'WP_Posts_List_Table', array( 'screen' => 'edit-' . $this->key ) );

		// get_column_headers() runs through both the manage_{screenid}_columns
		// and manage_{$post_type}_posts_columns filters
		$columns = (array) apply_filters( 'manage_edit-' . $this->key . '_columns', array() );
		$columns = array_filter( $columns );

		return $columns;
	}

	/**
	 * @since 2.0
	 */
	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $this->key ), ARRAY_N );
	}

	/**
	 * @since 2.0
	 */
	public function manage_value( $column_name, $post_id ) {

		if ( ! ( $column = $this->get_column_by_name( $column_name ) ) ) {
			return false;
		}

		global $post;

		// Setup post data for current post
		$post_old = $post;
		$post = get_post( $post_id );
		setup_postdata( $post );

		$value = $column->get_display_value( $post_id );

		$value = apply_filters( "cac/column/value", $value, $post_id, $column, $this->key );
		$value = apply_filters( "cac/column/value/{$this->type}", $value, $post_id, $column, $this->key );

		// Reset query to old post
		$post = $post_old;

		if ( $post ) {
			setup_postdata( $post );
		}

		echo $value;
	}

	/**
	 * @since 2.4.7
	 */
	public function manage_value_callback( $column_name, $post_id ) {

		$column = $this->get_column_by_name( $column_name );
		if ( $column && ! empty( $column->properties->handle ) ) {
			ob_start();
			$this->manage_value( $column_name, $post_id );
			ob_end_clean();
		}
		else {
			$this->manage_value( $column_name, $post_id );
		}
	}
}