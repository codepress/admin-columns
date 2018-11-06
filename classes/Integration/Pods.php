<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;

final class Pods extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-pods/ac-addon-pods.php',
			__( 'Pods', 'codepress-admin-columns' ),
			'assets/images/addons/pods.png',
			__( 'Display and edit Pods fields in the posts overview in seconds!', 'codepress-admin-columns' ),
			null,
			'pods'
		);
	}

	public function is_plugin_active() {
		return function_exists( 'pods' );
	}

	public function show_notice( Screen $screen ) {
		return in_array( $screen->get_id(), array(
			'toplevel_page_pods',
			'pods-admin_page_pods-settings',
		) );
	}

}