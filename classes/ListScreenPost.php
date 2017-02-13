<?php

abstract class AC_ListScreenPost extends AC_ListScreenWP {

	/**
	 * @var string Post type
	 */
	private $post_type;

	public function __construct( $post_type ) {
		$this->set_meta_type( 'post' );
	}

	public function get_post_type() {
		return $this->post_type;
	}

	protected function set_post_type( $post_type ) {
		$this->post_type = (string) $post_type;
	}

	/**
	 * @since NEWVERSION
	 * @return WP_Post Post object
	 */
	protected function get_object_by_id( $post_id ) {
		return get_post( $post_id );
	}

	/**
	 * @param $var
	 *
	 * @return string|false
	 */
	protected function get_post_type_label_var( $var ) {
		$post_type_object = get_post_type_object( $this->get_post_type() );

		return $post_type_object && isset( $post_type_object->labels->{$var} ) ? $post_type_object->labels->{$var} : false;
	}

}