<?php

namespace AC;

class AdminFactory {

	/**
	 * @var AdminFactoryInterface
	 */
	public static $factory;

	public static function set_factory( AdminFactoryInterface $admin_factory ) {
		self::$factory = $admin_factory;
	}

	public static function get_factory() {
		return self::$factory;
	}

}