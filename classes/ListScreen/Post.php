<?php

class AC_ListScreen_Post extends AC_ListScreen {

	/**
	 * @var string $post_type
	 */
	protected $post_type;

	public function __construct() {
		parent::__construct();

		$this->post_type = 'post';
		$this->key = $this->post_type;
		$this->type = $this->post_type;
		$this->base = 'edit';
		$this->screen = $this->base;
		$this->list_table = 'WP_Posts_List_Table';
		$this->menu_type = __( 'Post Type', 'codepress-admin-columns' );
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
	 * @param string $post_type
	 */
	public function set_post_type( $post_type ) {
		$this->post_type = $post_type;
		$this->key = $post_type;

		if ( $post_type != 'post' ) {
			$this->screen = $this->base . '-' . $post_type;
		}
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

	/**
	 * @see set_manage_value_callback()
	 */
	public function set_manage_value_callback() {
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