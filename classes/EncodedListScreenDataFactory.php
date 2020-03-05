<?php

namespace AC;

use ACP\Storage\ListScreens\DecoderAggregate;

final class EncodedListScreenDataFactory {

	/**
	 * @var EncodedListScreenData
	 */
	private static $instance;

	/**
	 * @return EncodedListScreenData
	 */
	public function create() {
		if ( self::$instance === null ) {
			self::$instance = new EncodedListScreenData( new DecoderAggregate( ListScreenTypes::instance() ) );
		}

		return self::$instance;
	}
}