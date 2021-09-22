<?php

namespace AC\Plugin;

abstract class Updater {

	/**
	 * @var Update[]
	 */
	protected $updates;

	/**
	 * @param Version|null $version
	 *
	 * @return bool
	 */
	abstract protected function update_stored_version( Version $version = null );

	/**
	 * @return bool
	 */
	abstract public function is_new_install();

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
				$this->update_stored_version( new Version( $update->get_version() ) );
			}
		}

		$this->update_stored_version();
	}

}