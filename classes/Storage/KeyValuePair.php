<?php

namespace AC\Storage;

interface KeyValuePair {

	/**
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function get( array $args = [] );

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