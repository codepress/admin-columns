<?php

namespace AC;

class Integrations extends ArrayIterator {

	public function __construct() {
		$integrations = array();

		$classes = Autoloader::instance()->get_class_names_from_dir( __NAMESPACE__ . '\Integration' );

		foreach ( $classes as $class ) {
			$integrations[] = new $class;
		}

		parent::__construct( $integrations );
	}

	/**
	 * @return Integration
	 */
	public function current() {
		return parent::current();
	}

}