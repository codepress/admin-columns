<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_DefaultPost extends AC_Column_Default {

	public function get_value( $id ) {
		return false;
	}

}