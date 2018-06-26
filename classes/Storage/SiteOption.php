<?php

namespace AC\Storage;

class SiteOption extends Option {

	/**
	 * @return mixed
	 */
	public function get() {
		return get_site_option( $this->key );
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