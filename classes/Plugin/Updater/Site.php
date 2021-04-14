<?php

namespace AC\Plugin\Updater;

use AC\Plugin;
use AC\Plugin\Updater;

class Site extends Updater {

	/**
	 * @var Plugin
	 */
	protected $plugin;

	/**
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	protected function update_stored_version( $version = null ) {
		$this->plugin->update_stored_version( $version );
	}

	protected function is_new_install() {
		return $this->plugin->is_new_install();
	}

}