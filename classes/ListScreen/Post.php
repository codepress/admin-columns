<?php

class AC_ListScreen_Post extends AC_ListScreenWP {

	/**
	 * @var string Post type
	 */
	private $post_type;

	public function __construct( $post_type ) {

		$this->type = 'post';
		$this->base = 'edit';
		$this->list_table = 'WP_Posts_List_Table';
		$this->meta_type = 'post';
		$this->post_type = $post_type;

		$this->set_key( $post_type );
		$this->set_group( 'post_type' );
		$this->set_screen_id( $this->base . '-' . $post_type );
	}

	protected function set_post_type( $post_type ) {
		$this->post_type = (string) $post_type;
	}

	public function get_post_type() {
		return $this->post_type;
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
		add_action( "manage_" . $this->get_post_type() . "_posts_custom_column", array( $this, 'manage_value' ), 100, 2 );
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
		$post_type_object = get_post_type_object( $this->get_post_type() );

		return $post_type_object && isset( $post_type_object->labels->{$var} ) ? $post_type_object->labels->{$var} : false;
	}

}