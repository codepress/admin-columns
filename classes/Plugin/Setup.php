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
	 * @var UpdateCollection|null
	 */
	private $updates;

	/**
	 * @var InstallCollection|null
	 */
	private $install;

	public function __construct( Version $version, StoredVersion $stored_version, UpdateCollection $updates = null, InstallCollection $install = null ) {
		$this->version = $version;
		$this->stored_version = $stored_version;
		$this->updates = $updates;
		$this->install = $install;
	}

	public function register() {
		add_action( 'init', [ $this, 'run' ], 1000 );
	}

	public function run() {
		if ( ! $this->can_install() ) {
			return;
		}

		if ( $this->is_new_install() ) {
			$this->stored_version->save( $this->version );

			return;
		}

		if ( $this->install ) {
			$this->install->install();
		}

		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		// TODO test network and single

		// Single site
		if ( $this->updates && ! is_network_admin() ) {
			array_map( [ $this, 'apply_update' ], $this->updates->get_copy() );
		}

		$this->stored_version->save( $this->version );
	}

	private function apply_update( Update $update ) {
		if ( $update->get_version()->is_gt( $this->stored_version->get() ) ) {
			$update->apply_update();

			$this->stored_version->save( $update->get_version() );
		}
	}

	/**
	 * @return bool
	 */
	public function is_new_install() {
		return ! $this->stored_version->get_previous()->is_valid() ||
		       ! $this->stored_version->get()->is_valid();
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