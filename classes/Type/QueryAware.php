<?php

namespace AC\Type;

interface QueryAware extends Url {

	/**
	 * @param string $key
	 * @param string $value
	 *
	 * @return void
	 */
	public function add_one( $key, $value );

	/**
	 * @param array $params
	 *
	 * @return void
	 */
	public function add( array $params = [] );

	/**
	 * @param string $key
	 *
	 * @return void
	 */
	public function remove( $key );

}