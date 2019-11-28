<?php

namespace AC;

class ListScreensDataCollecion {

	static $data = [];

	static function push( array $data ) {
		self::$data[] = $data;
	}

	static function get() {
		return self::$data;
	}

}