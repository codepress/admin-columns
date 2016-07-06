<?php
defined( 'ABSPATH' ) or die();

interface AC_Column_CustomFieldInterface {
	public function get_field_type();
	public function get_field_key();
	public function get_field();
}