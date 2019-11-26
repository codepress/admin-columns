<?php

namespace AC\ListScreenRepository;

// todo: rename ApiDataCollection
class FilePhpData {

	static $data = [];

	static function push( array $data ) {
		self::$data[] = $data;
	}

	static function get() {
		return self::$data;
	}

}