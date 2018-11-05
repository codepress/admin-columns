<?php

namespace AC;

class Integrations {

	/**
	 * @return Integration[]
	 */
	public static function get() {
		$integrations = array();

		$classes = Autoloader::instance()->get_class_names_from_dir( __NAMESPACE__ . '\Integration' );

		foreach ( $classes as $class ) {
			$integrations[] = new $class;
		}

		return $integrations;
	}

}