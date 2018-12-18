<?php
namespace AC\Admin;

use AC\Admin\Section\General;
use AC\Settings\Admin\General\ShowEditButton;

class GeneralSectionFactory {

	/** @var Section */
	private static $section;

	public static function create() {
		if ( null === self::$section ) {
			$general = new General();
			$general->register_setting( new ShowEditButton() );

			self::$section = $general;
		}

		return self::$section;
	}

}