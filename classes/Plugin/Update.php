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
	 * Check if this update needs to be applied
	 *
	 * @return bool
	 */
	public abstract function needs_update();

	/**
	 * @return bool
	 */
	protected function is_less_or_equal_stored_version() {
		return version_compare( $this->plugin->get_version(), $this->plugin->get_stored_version(), '<=' );
	}

	/**
	 * Apply this update
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
	 *
	 * @return void
	 */
	protected abstract function set_version();

	/**
	 * Update the stored version to self::$version
	 */
	protected function update_stored_version() {
		$this->plugin->update_stored_version( $this->version );
	}

}