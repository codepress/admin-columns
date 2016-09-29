<?php
defined( 'ABSPATH' ) or die();

abstract class AC_ColumnPostAbstract extends CPAC_Column  {

	public function get_post_type() {
		return method_exists( $this->get_list_screen(), 'get_post_type' ) ? $this->get_list_screen()->get_post_type() : false;
	}

}