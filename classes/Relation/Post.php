<?php

class AC_Relation_Post extends AC_Relation {

	/**
	 * @var stdClass
	 */
	private $post_type_object;

	public function __construct( $id ) {
		parent::__construct( $id );

		$this->post_type_object = get_post_type_object( $this->get_id() );
	}

	public function get_type() {
		return 'post';
	}

	public function get_post_type_object() {
		return $this->post_type_object;
	}

	public function get_labels() {
		if ( ! $this->post_type_object ) {
			return false;
		}

		return $this->post_type_object->labels;
	}

}