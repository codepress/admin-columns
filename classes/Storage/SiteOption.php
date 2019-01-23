<?php

namespace AC\Storage;

class SiteOption extends Option {

	const OPTION_DEFAULT = 'default';

	/**
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function get( array $args = array() ) {
		$args = array_merge( array(
			self::OPTION_DEFAULT => false,
		), $args );

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