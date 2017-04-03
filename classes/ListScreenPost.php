<?php

abstract class AC_ListScreenPost extends AC_ListScreen {

	/**
	 * @var string Post type
	 */
	private $post_type;

	/**
	 * @param string $post_type
	 */
	public function __construct( $post_type ) {
		$this->set_meta_type( 'post' );
		$this->set_post_type( $post_type );
	}

	/**
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * @param string $post_type
	 */
	protected function set_post_type( $post_type ) {
		$this->post_type = (string) $post_type;
	}

	/**
	 * @since 3.0
	 * @param int $post_id Post ID
	 * @return WP_Post
	 */
	protected function get_object_by_id( $post_id ) {
		return get_post( $post_id );
	}

	/**
	 * @param string $var
	 *
	 * @return string|false
	 */
	protected function get_post_type_label_var( $var ) {
		$post_type_object = get_post_type_object( $this->get_post_type() );

		return $post_type_object && isset( $post_type_object->labels->{$var} ) ? $post_type_object->labels->{$var} : false;
	}

}