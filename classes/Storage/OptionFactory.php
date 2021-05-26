<?php

namespace AC\Storage;

class OptionFactory {

	/**
	 * @param string $key
	 * @param bool   $network_active
	 *
	 * @return KeyValuePair
	 */
	public function create( $key, $network_active ) {
		return $network_active
			? new SiteOption( (string) $key )
			: new Option( (string) $key );
	}

}