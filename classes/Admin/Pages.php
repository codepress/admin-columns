<?php
namespace AC\Admin;

class Pages {

	/** @var Page[] */
	private static $pages;

	public static function register_page( Page $page  ) {
		self::$pages[] = $page;
	}

	public static function get_pages() {
		return self::$pages;
	}

}