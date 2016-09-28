<?php
defined( 'ABSPATH' ) or die();

class AC_ListTableManager_Post extends AC_ListTableManager_PostAbstract {

	public function __construct() {
		parent::__construct();

		$this->key = 'post';
		$this->type = 'post';
		$this->base = 'edit';
		$this->menu_type = __( 'Post Type', 'codepress-admin-columns' );
		$this->list_table = 'WP_Posts_List_Table';
	}

	/**
	 * @see set_manage_value_callback()
	 */
	public function set_manage_value_callback() {
		add_action( "manage_" . $this->post_type . "_posts_custom_column", array( $this, 'manage_value' ), 100, 2 );
	}

	/**
	 * @param string $post_type
	 */
	public function set_post_type( $post_type ) {
		$this->post_type = $post_type;
		$this->key = $post_type;
		$this->screen = $this->base . '-' . $post_type;

		$post_type_object = get_post_type_object( $this->get_post_type() );

		$this->label = $post_type_object->labels->name;
		$this->singular_label = $post_type_object->labels->singular_name;

		return $this;
	}

	/**
	 * @since 2.0
	 */
	public function get_screen_link() {
		return add_query_arg( array( 'post_type' => $this->get_post_type() ), parent::get_screen_link() );
	}

}