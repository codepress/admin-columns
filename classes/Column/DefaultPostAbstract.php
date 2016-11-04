<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_DefaultPostAbstract extends AC_Column_DefaultAbstract {

	public function get_value( $id ) {
		return false;
	}

}