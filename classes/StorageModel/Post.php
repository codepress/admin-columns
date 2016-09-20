<?php
defined( 'ABSPATH' ) or die();

class AC_StorageModel_Post extends AC_StorageModel_PostAbstract {

	public function init() {
		parent::init();

		$this->key = 'post';
		$this->type = 'post';
		$this->page = 'edit';
		$this->menu_type = __( 'Post Type', 'codepress-admin-columns' );
		$this->table_classname = 'WP_Posts_List_Table';
	}

	/**
	 * @param string $post_type
	 */
	public function set_post_type( $post_type ) {
		$this->post_type = $post_type;
		$this->key = $post_type;
		$this->screen = $this->page . '-' . $post_type;

		$post_type_object = get_post_type_object( $this->get_post_type() );

		$this->label = $post_type_object->labels->name;
		$this->singular_label = $post_type_object->labels->singular_name;

		add_action( "manage_" . $this->post_type . "_posts_custom_column", array( $this, 'manage_value' ), 100, 2 );

		return $this;
	}

	/**
	 * @since 2.0
	 */
	protected function get_screen_link() {
		return add_query_arg( array( 'post_type' => $this->get_post_type() ), admin_url( $this->page . '.php' ) );
	}

}