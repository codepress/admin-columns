<?php

namespace AC\Storage;

class SiteOption implements KeyValuePair {

	const OPTION_DEFAULT = 'default';

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @param string $key
	 */
	public function __construct( $key ) {
		$this->key = $key;
	}

	/**
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function get( array $args = [] ) {
		$args = array_merge( [
			self::OPTION_DEFAULT => false,
		], $args );

		wp_cache_delete( $this->key, 'site-options' );

		return get_site_option( $this->key, $args[ self::OPTION_DEFAULT ] );
	}

	/**
	 * @param $value
	 *
	 * @return bool
	 */
	public function save( $value ) {
		return update_site_option( $this->key, $value );
	}

	/**
	 * @return bool
	 */
	public function delete() {
		return delete_site_option( $this->key );
	}

}