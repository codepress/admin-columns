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

	public function __get( $helper ) {

		// Hotfix
		switch ( $helper ) {
			case 'string' :
				$helper = 'strings';

				break;
			case 'array' :
				$helper = 'arrays';

				break;
		}

		$class = 'AC\Helper\\' . ucfirst( $helper );

		if ( class_exists( $class ) ) {
			return new $class;
		}

		return false;
	}

}