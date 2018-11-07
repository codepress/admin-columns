<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;

final class Types extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-types/ac-addon-types.php',
			__( 'Toolset Types', 'codepress-admin-columns' ),
			'assets/images/addons/toolset-types.png',
			__( 'Display and edit Toolset Types fields in the posts overview in seconds!', 'codepress-admin-columns' ),
			null,
			'toolset-types'
		);
	}

	public function is_plugin_active() {
		return class_exists( 'Types_Main', false );
	}

	public function show_notice( Screen $screen ) {
		return in_array( $screen->get_id(), array(
			'toplevel_page_pods',
			'pods-admin_page_pods-settings',
		) );
	}

}