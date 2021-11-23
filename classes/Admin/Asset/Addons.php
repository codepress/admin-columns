<?php

namespace AC\Admin\Asset;

use AC\Asset\Location;
use AC\Asset\Script;

class Addons extends Script {

	public function __construct( $handle, Location $location = null ) {
		parent::__construct( $handle, $location, [ 'jquery' ] );
	}

	public function register() {
		parent::register();

		wp_localize_script( $this->handle, 'ACi18n', [
			'plugin_installed' => __( 'The Add-on %s is activated.', 'codepress-admin-columns' ),
		] );

		$this->add_inline_variable( 'AC', [
			'_ajax_nonce'      => wp_create_nonce( 'ac-ajax' ),
			'is_network_admin' => is_network_admin(),
		] );
	}

}