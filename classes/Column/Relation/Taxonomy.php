<?php

class AC_Column_Relation_Taxonomy extends AC_Column_Relation {

	/**
	 * @var stdClass
	 */
	private $taxonomy;

	public function get_type() {
		return 'taxonomy';
	}

	public function get_labels() {
		if ( ! $this->taxonomy ) {
			$taxonomy = get_taxonomy( $this->get_id() );

			if ( ! $taxonomy ) {
				return false;
			}

			$this->taxonomy = $taxonomy;
		}

		return $this->taxonomy->labels;
	}

}