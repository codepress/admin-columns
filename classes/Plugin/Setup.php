<?php

namespace AC\Plugin;

use AC\Plugin\Setup\Definition;
use AC\Storage\KeyValuePair;

abstract class Setup {

	/**
	 * @var Definition
	 */
	private $definition;

	/**
	 * @var Version
	 */
	protected $version;

	public function __construct( Definition $definition, Version $version ) {
		$this->definition = $definition;
		$this->version = $version;
	}

	/**
	 * @param Version $version
	 *
	 * @return void
	 */
	protected function update_stored_version( Version $version ) {
		$this->definition->get_storage()->save( (string) $version );
	}

	/**
	 * @return Version
	 */
	protected function get_stored_version() {
		return new Version( (string) $this->definition->get_storage()->get() );
	}

	private function update_stored_version_to_current() {
		$this->update_stored_version( $this->version );
	}

	/**
	 * @return bool
	 */
	abstract protected function is_new_install();

	private function install() {
		foreach ( $this->definition->get_installers() as $installer ) {
			$installer->install();
		}

		$this->update_stored_version_to_current();
	}

	/**
	 * @return void
	 */
	private function update() {
		foreach ( $this->definition->get_updates() as $update ) {
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