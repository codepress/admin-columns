<?php
namespace AC;

class Settings {

	/** @var Settings\Admin[] */
	private static $settings;

	public static function register_setting( Settings\Admin $setting  ) {
		self::$settings[] = $setting;
	}

	public static function get_settings() {
		return self::$settings;
	}

}