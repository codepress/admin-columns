<?php

namespace AC\Admin\Asset;

use AC\Asset\Location;
use AC\Asset\Script;

class Settings extends Script {

	public function __construct( $handle, Location $location = null ) {
		parent::__construct( $handle, $location );
	}

	public function register() {
		parent::register();

		wp_add_inline_script(
			$this->get_handle(),
			sprintf(
				"var AC = %s;",
				json_encode( [
					'_ajax_nonce'      => wp_create_nonce( 'ac-ajax' ),
					'is_network_admin' => is_network_admin(),
				] )
			),
			'before'
		);
	}

}