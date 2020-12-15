<?php

namespace AC\Plugin;

abstract class Updater {

	/**
	 * @var Update[]
	 */
	protected $updates;

	/**
	 * @param Update $update
	 *
	 * @return $this
	 */
	public function add_update( Update $update ) {
		$this->updates[ $update->get_version() ] = $update;

		return $this;
	}

	public function parse_updates() {
		if ( $this->is_new_install() ) {
			$this->update_stored_version();

			return;
		}

		if ( empty( $this->updates ) ) {
			return;
		}

		// Sort by version number
		uksort( $this->updates, 'version_compare' );

		foreach ( $this->updates as $update ) {
			if ( $update->needs_update() ) {
				$update->apply_update();
				$this->update_stored_version( $update->get_version() );
			}
		}

		$this->update_stored_version();
	}

	/**
	 * @param null $version
	 *
	 * @return bool
	 */
	abstract protected function update_stored_version( $version = null );

	/**
	 * @return bool
	 */
	abstract protected function is_new_install();

}