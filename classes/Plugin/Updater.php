<?php

namespace AC\Plugin;

// TODO remove
abstract class Updater {

	/**
	 * @var StoredVersion
	 */
	protected $stored_version;

	/**
	 * @var Version
	 */
	protected $version;

	/**
	 * @var Update[]
	 */
	protected $updates;

	public function __construct( StoredVersion $stored_version, Version $version, array $updates = [] ) {
		$this->stored_version = $stored_version;
		$this->version = $version;
		$this->updates = $updates;
	}

	/**
	 * @return bool
	 */
	abstract public function is_new_install();

	// TODO maybe move to Setup
	public function parse_updates() {
		if ( $this->is_new_install() ) {
			$this->stored_version->save( $this->version );

			return;
		}

		if ( empty( $this->updates ) ) {
			return;
		}

		// Sort by version number
		uksort( $this->updates, 'version_compare' );

		foreach ( $this->updates as $update ) {
			if ( $update->get_version()->is_gt( $this->stored_version->get() ) ) {
				$update->apply_update();

				$this->stored_version->save( $update->get_version() );
			}
		}

		$this->stored_version->save( $this->version );
	}

}