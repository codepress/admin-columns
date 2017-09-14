<?php

class AC_Column_Relation_Post extends AC_Column_Relation {

	/**
	 * @var stdClass
	 */
	private $post_type_object;

	public function get_type() {
		return 'post';
	}

	public function get_labels() {
		if ( ! $this->post_type_object ) {
			$post_type_object = get_post_type_object( $this->get_id() );

			if ( ! $post_type_object ) {
				return false;
			}

			$this->post_type_object = $post_type_object;
		}

		return $this->post_type_object->labels;
	}

}