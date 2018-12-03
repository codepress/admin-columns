<?php

namespace AC\Admin\Menu;

use AC\Admin\Menu;

class Site extends Menu {

	/** @var self */
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}