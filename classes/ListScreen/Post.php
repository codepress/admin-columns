<?php

class AC_ListScreen_Post extends AC_ListScreenWP {

	/**
	 * @var string $post_type
	 */
	protected $post_type;

	public function __construct() {
		parent::__construct();

		$this->set_post_type();

		$this->type = 'post';
		$this->base = 'edit';
		$this->list_table = 'WP_Posts_List_Table';
		$this->menu_type = __( 'Post Type', 'codepress-admin-columns' );
		$this->meta_type = 'post';
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
	 * @since 2.1.1
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * @param null|string $post_type
	 *
	 * @return $this;
	 */
	public function set_post_type( $post_type = null ) {
		if ( null === $post_type ) {
			$post_type = 'post';
		}

		$this->post_type = $post_type;
		$this->key = $post_type;
		$this->screen = $this->base . '-' . $post_type;

		return $this;
	}

	/**
	 * @since NEWVERSION
	 * @return WP_Post Post object
	 */
	protected function get_object_by_id( $post_id ) {
		return get_post( $post_id );
	}

	/**
	 * @since 2.4.7
	 */
	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

	public function set_manage_value_callback() {
		// located in WP_Posts_List_Table::column_default()
		add_action( "manage_" . $this->post_type . "_posts_custom_column", array( $this, 'manage_value' ), 100, 2 );
	}

	/**
	 * @since 2.0
	 */
	public function get_screen_link() {
		return add_query_arg( array( 'post_type' => $this->get_post_type() ), parent::get_screen_link() );
	}

	/**
	 * @return string|false
	 */
	public function get_label() {
		return $this->get_post_type_label_var( 'name' );
	}

	/**
	 * @return false|string
	 */
	public function get_singular_label() {
		return $this->get_post_type_label_var( 'singular_name' );
	}

	/**
	 * @param $var
	 *
	 * @return string|false
	 */
	private function get_post_type_label_var( $var ) {
		$post_type_object = get_post_type_object( $this->post_type );

		return $post_type_object && isset( $post_type_object->labels->{$var} ) ? $post_type_object->labels->{$var} : false;
	}

}