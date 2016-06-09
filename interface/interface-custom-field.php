<?php
defined( 'ABSPATH' ) or die();

interface CPAC_Interface_Custom_Field {
	public function get_field_type();
	public function get_field_key();
}