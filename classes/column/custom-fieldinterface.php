<?php
defined( 'ABSPATH' ) or die();

interface CPAC_Column_Custom_FieldInterface {
	public function get_field_type();
	public function get_field_key();
	public function get_field();
}