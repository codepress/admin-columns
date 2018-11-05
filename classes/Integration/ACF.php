<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;

class ACF extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-acf/ac-addon-acf.php',
			__( 'Advanced Custom Fields', 'codepress-admin-columns' ),
			'assets/images/addons/acf.png',
			'https://www.advancedcustomfields.com/'
		);
	}

	public function is_plugin_active() {
		return class_exists( 'acf', false );
	}

	public function show_notice( Screen $screen ) {
		return 'edit-acf-field-group' === $screen->get_screen()->id;
	}

}