<?php

namespace AC\Plugin;

use AC\Capabilities;
use AC\Registrable;

class Setup implements Registrable {

	/**
	 * @var Version
	 */
	private $version;

	/**
	 * @var StoredVersion
	 */
	private $stored_version;

	/**
	 * @var Updater
	 */
	private $updater;

	/**
	 * @var Install|null
	 */
	private $install;

	public function __construct( Version $version, StoredVersion $stored_version, Updater $updater, Install $install = null ) {
		$this->version = $version;
		$this->stored_version = $stored_version;
		$this->updater = $updater;
		$this->install = $install;
	}

	public function register() {
		add_action( 'init', [ $this, 'run' ], 1000 );
	}

	public function run() {
		if ( ! $this->can_install() ) {
			return;
		}

		if ( $this->install ) {
			$this->install->install();
		}

		if ( current_user_can( Capabilities::MANAGE ) && ! is_network_admin() ) {
			$this->updater->parse_updates();
		}
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
		if ( $this->version->is_not_equal( $this->stored_version->get() ) ) {
			return true;
		}

		// Run installer when the current version can not be read from the plugin's header file
		if ( ! $this->version->is_valid() && ! $this->stored_version->get()->is_valid() ) {
			return true;
		}

		return false;
	}

}