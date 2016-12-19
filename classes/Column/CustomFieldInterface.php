<?php

interface AC_Column_CustomFieldInterface {

	/**
	 * @return string
	 */
	public function get_field_type();

	/**
	 * @return string Unique key for the Custom Field
	 */
	public function get_field_key();

	// remove?
	public function get_field();
}