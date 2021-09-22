<?php

namespace AC\Plugin\Updater;

use AC\Plugin\Updater;

class Network extends Updater {

	/**
	 * Current and before version 5 check
	 * @return bool
	 */
	public function is_new_install() {
		if ( ! $this->stored_version->get_previous()->is_valid() ) {
			return true;
		}

		if ( $this->stored_version->get()->is_valid() ) {
			return false;
		}

		return empty( get_site_option( 'cpupdate_cac-pro' ) );
	}

}