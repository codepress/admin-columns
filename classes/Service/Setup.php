<?php

namespace AC\Service;

use AC\Capabilities;
use AC\Registrable;
use AC\Plugin;

final class Setup implements Registrable {

	private $setup;

	public function __construct( Plugin\Setup $setup ) {
		$this->setup = $setup;
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

		// TODO David why is this here? What does it do?
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		if ( $this->updater && ! $this->new_install_check->is_new_install() ) {
			$this->updater->apply_updates();
		}

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