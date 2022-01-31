<?php

namespace AC\Plugin;

use AC\Storage\KeyValuePair;

abstract class Setup {

	/**
	 * @var KeyValuePair
	 */
	private $storage;

	/**
	 * @var Version
	 */
	private $version;

	/**
	 * @var InstallCollection
	 */
	private $installers;

	/**
	 * @var UpdateCollection
	 */
	private $updates;

	public function __construct(
		KeyValuePair $storage,
		Version $version,
		InstallCollection $installers,
		UpdateCollection $updates
	) {
		$this->storage = $storage;
		$this->version = $version;
		$this->installers = $installers;
		$this->updates = $updates;
	}

	/**
	 * @param Version $version
	 *
	 * @return void
	 */
	protected function update_stored_version( Version $version ) {
		$this->storage->save( (string) $version );
	}

	/**
	 * @return Version
	 */
	protected function get_stored_version() {
		return new Version( (string) $this->storage->get() );
	}

	private function update_stored_version_to_current() {
		$this->update_stored_version( $this->version );
	}

	/**
	 * @return bool
	 */
	abstract protected function is_new_install();

	private function install() {
		foreach ( $this->installers as $installer ) {
			$installer->install();
		}

		$this->update_stored_version_to_current();
	}

	/**
	 * @return void
	 */
	private function update() {
		foreach ( $this->updates as $update ) {
			if ( ! $update->needs_update( $this->get_stored_version() ) ) {
				continue;
			}

			$update->apply_update();

			$this->update_stored_version( $update->get_version() );
		}

		$this->update_stored_version_to_current();
	}

	/**
	 * @param bool $force_install
	 *
	 * @return void
	 */
	public function run( $force_install = false ) {
		if ( $force_install === true ) {
			$this->install();
		}

		if ( $this->version->is_equal( $this->get_stored_version() ) ) {
			return;
		}

		if ( $this->is_new_install() ) {
			$this->install();
		} else {
			$this->update();
		}
	}

}