<?php

namespace AC\Admin\Asset;

use AC\Asset\Location;
use AC\Asset\Script;
use AC\Nonce;

class Addons extends Script {

	public function __construct( $handle, Location $location = null ) {
		parent::__construct( $handle, $location, [ 'jquery' ] );
	}

	public function register() {
		parent::register();

		$nonce = new Nonce\Ajax();

		wp_localize_script( $this->handle, 'ACi18n', [
			'plugin_installed' => __( 'The Add-on %s is activated.', 'codepress-admin-columns' ),
		] );

		$this->add_inline_variable( 'AC', [
			Nonce\Ajax::NAME   => $nonce->create(),
			'is_network_admin' => is_network_admin(),
		] );
	}

}