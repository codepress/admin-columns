<?php
defined( 'ABSPATH' ) or die();

class AC_ListScreen_Post extends AC_ListScreen_PostAbstract {

	public function __construct( $post_type ) {
		parent::__construct();

		$this->type = 'post';
		$this->base = 'edit';
		$this->menu_type = __( 'Post Type', 'codepress-admin-columns' );
		$this->list_table = 'WP_Posts_List_Table';

		$this->post_type = $post_type;
		$this->key = $this->post_type;
		$this->screen = $this->base . '-' . $this->post_type;
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