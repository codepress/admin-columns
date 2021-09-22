<?php

namespace AC\Plugin;

use AC\Autoloader;

class UpdatesFactory {

	/**
	 * @param string $namespace
	 *
	 * @return Update[]
	 */
	public static function create_from_dir( $dir, Version $version ) {
		$updates = [];

		foreach ( Autoloader::instance()->get_class_names_from_dir( $dir ) as $class ) {
			$updates[] = new $class( $version->get_value() );
		}

		return $updates;
	}

}