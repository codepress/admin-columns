<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class AC_Helper
 *
 * Implements __call to work around any keyword restrictions for PHP versions > 7
 *
 * @method AC_Helper_Array array
 * @method AC_Helper_Date date
 * @method AC_Helper_Post post
 * @method AC_Helper_String string
 * @method AC_Helper_User user
 * @method AC_Helper_Query query
 */
class AC_Helper {

	public function __call( $name, $arguments ) {
		$class = 'AC_Helper_' . ucfirst( $name );

		if ( class_exists( 'AC_Helper_' . ucfirst( $name ) ) ) {
			return new $class;
		}

		return false;
	}

}