<?php

namespace AC\Integration;

use AC\Integrations;

interface Filter {

	/**
	 * @param Integrations $integrations
	 *
	 * @return Integrations
	 */
	public function filter( Integrations $integrations );

}