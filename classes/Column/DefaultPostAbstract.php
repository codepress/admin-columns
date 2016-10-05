<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_DefaultPostAbstract extends AC_Column_DefaultAbstract {

	public function get_post_type() {
		return method_exists( $this->get_list_screen(), 'get_post_type' ) ? $this->get_list_screen()->get_post_type() : false;
	}

}