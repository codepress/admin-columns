<?php

interface AC_Column_RelationInterface {

	/**
	 * Return information about the relation this column has.
	 *
	 * @return AC_Column_Relation
	 */
	public function get_relation_object();

}