<?php
defined( 'ABSPATH' ) or die();

class CPAC_Storage_Model_Post extends CPAC_Storage_Model {

	/**
	 * @since 2.0
	 */
	public function __construct( $post_type ) {

		$this->key = $post_type;
		$this->post_type = $post_type;
		$this->type = 'post';
		$this->meta_type = 'post';
		$this->page = 'edit';
		$this->screen = $this->page . '-' . $this->post_type;
		$this->menu_type = __( 'Post Type', 'codepress-admin-columns' );
		$this->table_classname = 'WP_Posts_List_Table';

		$this->set_labels();

		parent::__construct();
	}

	/**
	 * @since NEWVERSION
	 */
	public function init_column_values() {
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'manage_value_callback' ), 100, 2 );
	}

	/**
	 * @since 2.4.7
	 */
	public function get_posts( $args = array() ) {
		$defaults = array(
			'posts_per_page' => -1,
			'post_status'    => apply_filters( 'cac/get_posts/post_status', array( 'any', 'trash' ), $this ),
			'post_type'      => $this->get_post_type(),
			'fields'         => 'ids',
			'no_found_rows'  => 1,
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
	public function get_original_column_value( $column_name, $id ) {
		global $post;

		// Setup post data for current post
		$post_old = $post;
		$post = get_post( $id );
		setup_postdata( $post );

		// Remove Admin Columns action for this column's value
		remove_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'manage_value_callback' ), 100 );

		ob_start();
		// Run WordPress native actions to display column content
		if ( is_post_type_hierarchical( $this->post_type ) ) {
			do_action( 'manage_pages_custom_column', $column_name, $id );
		}
		else {
			do_action( 'manage_posts_custom_column', $column_name, $id );
		}

		do_action( "manage_{$this->post_type}_posts_custom_column", $column_name, $id );

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

		if ( post_type_supports( $this->get_post_type(), 'title' ) ) {
			$defaults[] = 'title';
		}
		if ( post_type_supports( $this->get_post_type(), 'comments' ) ) {
			$defaults[] = 'comments';
		}

		if ( in_array( $this->get_post_type(), array( 'post', 'page' ) ) ) {
			$defaults[] = 'cb';
			$defaults[] = 'author';
			$defaults[] = 'categories';
			$defaults[] = 'parent';
			$defaults[] = 'tags';
		}

		return $defaults;
	}

	/**
	 * @since 2.5
	 */
	public function get_default_column_widths() {
		return array(
			'author'     => array( 'width' => 10 ),
			'categories' => array( 'width' => 15 ),
			'tags'       => array( 'width' => 15 ),
			'date'       => array( 'width' => 10 ),
		);
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

		return ( $this->get_post_type() === $post_type ) && parent::is_current_screen();
	}

	/**
	 * @since NEWVERSION
	 * @return WP_Post Post object
	 */
	protected function get_object_by_id( $id ) {
		return get_post( $id );
	}

	/**
	 * @since 2.0
	 */
	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $this->get_post_type() ), ARRAY_N );
	}

	/**
	 * @since 2.0
	 */
	public function manage_value( $column_name, $post_id ) {
		// Setup post data for current post
		// TODO: remove?
		global $post;
		$post_old = $post;
		$post = get_post( $post_id );
		setup_postdata( $post );

		$value = $this->get_manage_value( $column_name, $post_id );

		// Reset query to old post
		// TODO: remove?
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
		if ( $column && $column->get_handle() ) {
			ob_start();
			$this->manage_value( $column_name, $post_id );
			ob_end_clean();
		}
		else {
			$this->manage_value( $column_name, $post_id );
		}
	}
}