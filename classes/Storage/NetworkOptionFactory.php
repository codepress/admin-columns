<?php

namespace AC\Storage;

class NetworkOptionFactory implements KeyValueFactory {

	public function create( string $key ): KeyValuePair
    {
		return new SiteOption( $key );
	}

}