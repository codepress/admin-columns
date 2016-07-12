<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_MediaAbstract extends CPAC_Column {

	public function get_post_type() {
		return $this->get_storage_model()->get_post_type();
	}

}