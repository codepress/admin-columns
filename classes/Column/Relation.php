<?php

interface AC_Column_RelationInterface {

	/**
	 * @return bool
	 */
	public function is_relation_taxonomy();

	/**
	 * @return bool
	 */
	public function is_relation_post_type();

	/**
	 * Should return a relation object or false on failure
	 *
	 * @return stdClass|false
	 */
	public function get_relation_object();

	/**
	 * Return the post_type or taxonomy
	 *
	 * @return string
	 */
	public function get_relation_object_type();

}