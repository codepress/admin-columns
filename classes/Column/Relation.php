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
	 * @return stdClass|null
	 */
	public function get_relation_object();

	/**
	 * @return string
	 */
	public function get_relation_object_type();

}