<?php
namespace AC\Admin;

use AC\Admin;

class AdminFactory {

	/**
	 * @param bool $is_network
	 *
	 * @return Admin
	 */
	public function create( $is_network ) {

		if ( $is_network ) {
			return new Admin( 'settings.php', 'network_admin_menu', network_admin_url() );
		} else {
			return new Admin( 'options-general.php', 'admin_menu', admin_url() );
		}
	}

}