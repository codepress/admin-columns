<?php

namespace AC\Storage;

interface KeyValuePairStorage {

	public function get();

	public function save( $value );

	public function delete();

}