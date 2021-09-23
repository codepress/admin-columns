<?php

namespace AC\Plugin;

use AC\ArrayIterator;
use AC\Autoloader;

/**
 * @var Update[] $array
 */
class UpdateCollection extends ArrayIterator {

	public function __construct( array $array = [] ) {
		parent::__construct( $array );

		$this->sort_by_version();
	}

	private function sort_by_version() {
		usort( $this->array, function ( Update $a, Update $b ) {
			return version_compare( $a->get_version(), $b->get_version() );
		} );
	}

	public static function create_by_namespace( $namespace ) {
		return new self( array_map( static function ( $class ) {
			return new $class;
		}, Autoloader::instance()->get_class_names_from_dir( $namespace ) ) );
	}

}