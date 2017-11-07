<?php

abstract class AC_Plugin_Update {

	/**
	 * @var string
	 */
	protected $version;

	/**
	 * @var AC_Plugin
	 */
	protected $plugin;

	public function __construct( AC_Plugin $plugin ) {
		$this->plugin = $plugin;
		$this->set_version();
	}

	/**
	 * Apply the update
	 *
	 * @return void
	 */
	public abstract function apply_update();

	/**
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Set the version this update applies to
	 */
	protected abstract function set_version();

	/**
	 * Update the stored version to self::$version
	 */
	protected function update_version() {
		$this->plugin->update_stored_version( $this->version );
	}

}