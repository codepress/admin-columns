<?php
defined( 'ABSPATH' ) or die();

class AC_StorageModel_Post extends CPAC_Storage_Model {

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
		add_action( "manage_" . $this->get_post_type() . "_posts_custom_column", array( $this, 'manage_value' ), 100, 2 );
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
		$post_type_object = get_post_type_object( $this->get_post_type() );

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

		// Start
		ob_start();

		/** @var WP_List_Table|WP_Posts_List_Table $table */
		$table = $this->get_list_table();

		if ( method_exists( $table, 'column_' . $column_name ) ) {
			call_user_func( array( $table, 'column_' . $column_name ), $post );
		}
		else if ( method_exists( $table, 'column_default' ) ) {
			$table->column_default( $post, $column_name );
		}

		// Restore original post object
		$post = $post_old;
		if ( $post ) {
			setup_postdata( $post );
		}

		return ob_get_clean();
	}

	/**
	 * @since 2.0
	 */
	protected function get_screen_link() {
		return add_query_arg( array( 'post_type' => $this->get_post_type() ), admin_url( $this->page . '.php' ) );
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
	 * @since 2.4.7
	 */
	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

}