<?php

namespace AC\Plugin\Setup;

use AC\Plugin\Setup;
use AC\Plugin\Version;

final class Network extends Setup {

	public function __construct( Definition\Network $definition, Version $version ) {
		parent::__construct( $definition, $version );
	}

	protected function is_new_install() {
		$result = get_site_option( 'cpupdate_cac-pro' );

		if ( $result ) {
			return false;
		}

		return ! $this->get_stored_version()->is_valid();
	}

}