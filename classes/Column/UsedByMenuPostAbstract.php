<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_UsedByMenuPostAbstract extends AC_Column_UsedByMenuAbstract {

	public function get_object_type() {
		return $this->get_post_type();
	}

	private function get_post_type() {
		return method_exists( $this->get_list_screen(), 'get_post_type' ) ? $this->get_list_screen()->get_post_type() : false;
	}

}