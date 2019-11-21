<?php

namespace AC\ListScreenRepository;

class FilePhpData {

	static $data = [];

	static function push( array $data ) {
		self::$data[] = $data;
	}

	static function get() {
		return self::$data;
	}

}