<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_UsedByMenuPostAbstract extends AC_Column_UsedByMenuAbstract {

	public function get_object_type() {
		return $this->get_post_type();
	}

}