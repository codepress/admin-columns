<?php

namespace AC\Column;

use AC;

interface Relation {

	/**
	 * Return information about the relation this column has.
	 * @return AC\Relation
	 */
	public function get_relation_object();

}