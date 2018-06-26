<?php

namespace AC\Storage;

interface KeyValuePair {

	public function get();

	public function save( $value );

	public function delete();

}