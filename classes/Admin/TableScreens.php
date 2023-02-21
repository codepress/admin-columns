<?php
declare( strict_types=1 );

namespace AC\Admin;

class TableScreens {

	private static $screens = [];

	public static function add( TableScreen $screen ): void {
		self::$screens[] = $screen;
	}

	/**
	 * @return TableScreen[]
	 */
	public static function get_screens(): array {
		return self::$screens;
	}

}