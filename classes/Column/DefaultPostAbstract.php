<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_DefaultPostAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['is_cloneable'] = false;
		$this->properties['original'] = true;
	}

	public function get_post_type() {
		return method_exists( $this->get_list_screen(), 'get_post_type' ) ? $this->get_list_screen()->get_post_type() : false;
	}

}