<?php

namespace AC\Storage;

class NetworkOptionFactory implements KeyValueFactory {

	public function create( $key ) {
		return new SiteOption( $key );
	}

}