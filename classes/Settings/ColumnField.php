<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AC_Settings_ColumnField {

	abstract function get_type();
	abstract function display();

}