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

	public function parse_updates() {
		if ( $this->plugin->is_new_install() ) {
			$this->plugin->update_stored_version();

			return;
		}

		// Sort by version number
		uksort( $this->updates, 'version_compare' );

		foreach ( $this->updates as $update ) {
			if ( $update->needs_update() ) {

				$update->apply_update();
				$this->plugin->update_stored_version( $update->get_version() );
			}
		}

		$this->plugin->update_stored_version();
	}

}