<?php

namespace AC\Settings;

use AC\Capabilities;
use AC\Registrable;

class General implements Registrable {

	const NAME = 'cpac_general_options';
	const GROUP = 'admin-columns-general-settings';

	public function register() {
		register_setting( self::GROUP, self::NAME );

		add_filter( 'option_page_capability_' . self::GROUP, [ $this, 'set_capability' ] );
	}

	/**
	 * @return string
	 */
	public function set_capability() {
		return Capabilities::MANAGE;
	}

}