<?php

namespace AC;

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
			self::$instance = new EncodedListScreenData();
		}

		return self::$instance;
	}
}