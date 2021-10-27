<?php

namespace AC\Admin;

use AC;

class PageNetworkFactoryAggregate implements PageFactoryAggregateInterface {

	/**
	 * @var PageFactoryInterface[]
	 */
	private static $factories = [];

	public function add( $slug, PageFactoryInterface $factory ) {
		self::$factories[ $slug ] = $factory;

		return $this;
	}

	public function create( $slug ) {
		if ( ! isset( self::$factories[ $slug ] ) ) {
			return null;
		}

		return self::$factories[ $slug ]->create();
	}

}