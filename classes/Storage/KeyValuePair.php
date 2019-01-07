<?php

namespace AC\Storage;

interface KeyValuePair {

	/**
	 * @return mixed
	 */
	public function get();

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function save( $value );

	/**
	 * @return bool
	 */
	public function delete();

}