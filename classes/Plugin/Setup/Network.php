<?php

namespace AC\Plugin\Setup;

use AC\Plugin\Setup;

final class Network extends Setup {

	protected function is_new_install() {
		$result = get_site_option( 'cpupdate_cac-pro' );

		if ( $result ) {
			return false;
		}

		return ! $this->get_stored_version()->is_valid();
	}

}