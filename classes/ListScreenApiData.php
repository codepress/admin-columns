<?php

namespace AC;

// TODO David get a feel for this how and what. Is this a repositoru of some sort?
class ListScreenApiData {

	static $data = [];

	static function push( array $data ) {
		self::$data[] = $data;
	}

	static function get() {
		return self::$data;
	}

}