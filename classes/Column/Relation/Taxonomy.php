<?php

class AC_Column_Relation_Taxonomy extends AC_Column_Relation {

	/**
	 * @var stdClass
	 */
	private $taxonomy;

	public function __construct( $id ) {
		parent::__construct( $id );

		$this->taxonomy = get_taxonomy( $this->get_id() );
	}

	public function get_type() {
		return 'taxonomy';
	}

	public function get_taxonomy() {
		return $this->taxonomy;
	}

	public function get_labels() {
		if ( ! $this->taxonomy ) {
			return false;
		}

		return $this->taxonomy->labels;
	}

}