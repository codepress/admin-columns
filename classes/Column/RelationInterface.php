<?php

namespace AC\Column;

use AC\Relation;

// TODO: change class name to Relation?
interface RelationInterface {

	/**
	 * Return information about the relation this column has.
	 *
	 * @return Relation
	 */
	public function get_relation_object();

}