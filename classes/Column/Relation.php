<?php

namespace AC\Column;

interface Relation {

	/**
	 * Return information about the relation this column has.
	 *
	 * @return Relation
	 */
	public function get_relation_object();

}