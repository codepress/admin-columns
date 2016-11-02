<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AC_Settings_Field_Label extends AC_Settings_ColumnField {

	public function get_type() {
		return 'width';
	}

	public function display() {
		echo 'Width settings';
	}

}