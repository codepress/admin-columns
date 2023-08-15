<?php

namespace AC\Storage;

class OptionFactory implements KeyValueFactory {

	public function create( string $key ): KeyValuePair
    {
		return new Option( $key );
	}

}