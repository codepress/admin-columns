<?php

namespace AC\Storage;

class OptionFactory implements KeyValueFactory {

	public function create( $key ) {
		return new Option( $key );
	}

}