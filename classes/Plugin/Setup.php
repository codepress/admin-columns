<?php

namespace AC\Plugin;

use AC\Storage\KeyValuePair;

abstract class Setup {

	/**
	 * @var KeyValuePair
	 */
	private $version_storage;

	/**
	 * @var Version
	 */
	protected $version;

	/**
	 * @var InstallCollection
	 */
	protected $installers;

	/**
	 * @var UpdateCollection
	 */
	protected $updates;

	public function __construct(
		KeyValuePair $version_storage,
		Version $version,
		InstallCollection $installers,
		UpdateCollection $updates
	) {
		$this->version_storage = $version_storage;
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
		$this->version_storage->save( (string) $version );
	}

	/**
	 * @return Version
	 */
	protected function get_stored_version() {
		return new Version( (string) $this->version_storage->get() );
	}

	/**
	 * @return bool
	 */
	abstract protected function is_new_install();

	private function install() {
		foreach ( $this->installers as $installer ) {
			$installer->install();
		}
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

		$this->update_stored_version( $this->version );
	}

	/**
	 * @return void
	 */
	public function run( $force_update = false ) {
		if ( $force_update === false && ! $this->requires_update() ) {
			return;
		}

		// Always run the installers, they should be written as idempotent calls
		$this->install();

		if ( ! $this->is_new_install() ) {
			$this->update();
		}
	}

	/**
	 * @return bool
	 */
	private function requires_update() {
		// Run installer when the current version can not be read / does not exist
		if ( $this->is_new_install() ) {
			return true;
		}

		// Run installer when the current version is not equal to its stored version
		if ( $this->version->is_not_equal( $this->get_stored_version() ) ) {
			return true;
		}

		return false;
	}

}