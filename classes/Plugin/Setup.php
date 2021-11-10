<?php

namespace AC\Plugin;

use AC\Capabilities;
use AC\Registrable;

class Setup implements Registrable {

	/**
	 * @var VersionStorage
	 */
	private $version_storage;

	/**
	 * @var Version
	 */
	private $version;

	/**
	 * @var Updater
	 */
	private $updater;

	/**
	 * @var Installer|null
	 */
	private $installer;

	public function __construct( VersionStorage $version_storage, Version $version, Updater $updater, Installer $installer = null ) {
		$this->version_storage = $version_storage;
		$this->version = $version;
		$this->updater = $updater;
		$this->installer = $installer;
	}

	public function register() {
		add_action( 'init', [ $this, 'run' ], 1000 );
	}

	public function run() {
		if ( ! $this->can_install() ) {
			return;
		}

		if ( $this->installer ) {
			$this->installer->install();
		}

		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		$this->updater->apply_updates();

		$this->version_storage->save( $this->version );
	}

	/**
	 * @return bool
	 */
	private function can_install() {

		// Run installer manually
		if ( '1' === filter_input( INPUT_GET, 'ac-force-install' ) ) {
			return true;
		}

		// Run installer when the current version is not equal to its stored version
		if ( $this->version->is_not_equal( $this->version_storage->get() ) ) {
			return true;
		}

		// Run installer when the current version can not be read from the plugin's header file
		if ( ! $this->version->is_valid() && ! $this->version_storage->get()->is_valid() ) {
			return true;
		}

		return false;
	}

}