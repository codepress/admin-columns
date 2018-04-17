<?php

namespace AC;

/**
 * Class AC_Helper
 *
 * Implements __call to work around any keyword restrictions for PHP versions > 7
 *
 * @property Helper\Arrays   array
 * @property Helper\Date     date
 * @property Helper\Image    image
 * @property Helper\Post     post
 * @property Helper\Strings  string
 * @property Helper\Taxonomy taxonomy
 * @property Helper\User     user
 * @property Helper\Icon     icon
 * @property Helper\Html     html
 * @property Helper\Media    media
 * @property Helper\Network  network
 * @property Helper\File     file
 */
final class Helper {

	// TODO: test
	public function __get( $helper ) {
		$class = 'AC\Helper\\' . ucfirst( $helper );

		// TODO: hotfix
		if ( $helper === 'string' ) {
			$class = 'AC\Helper\Strings';
		}

		// TODO: hotfix
		if ( $helper === 'array' ) {
			$class = 'AC\Helper\Arrays';
		}

		if ( class_exists( $class ) ) {
			return new $class;
		}

		return false;
	}

}