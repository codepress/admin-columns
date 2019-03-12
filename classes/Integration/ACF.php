<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;

final class ACF extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-acf/ac-addon-acf.php',
			__( 'Advanced Custom Fields', 'codepress-admin-columns' ),
			'assets/images/addons/acf.png',
			__( 'Display and edit ACF fields in the posts overview in seconds!', 'codepress-admin-columns' ),
			'https://www.advancedcustomfields.com',
			'advanced-custom-fields'
		);
	}

	public function is_plugin_active() {
		return class_exists( 'acf', false );
	}

	public function show_notice( Screen $screen ) {
		return in_array( $screen->get_id(), array(
			'edit-acf-field-group',
			'acf-field-group',
		) );
	}

}