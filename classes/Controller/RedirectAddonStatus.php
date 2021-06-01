<?php

namespace AC\Controller;

use AC\Admin\Page\Addons;
use AC\Integration;
use AC\Integrations;
use AC\Plugin;
use AC\PluginInformation;
use AC\Registrable;

class RedirectAddonStatus implements Registrable {

	/**
	 * @var Integrations
	 */
	private $integrations;

	/**
	 * @var Plugin
	 */
	private $plugin;

	public function __construct( Integrations $integrations, PluginInformation $plugin ) {
		$this->integrations = $integrations;
		$this->plugin = $plugin;
	}

	/**
	 * @return string
	 */
	private function get_url() {

		// Determine runtime if network is active
		return $this->plugin->is_network_active()
			? ac_get_admin_network_url( Addons::NAME )
			: ac_get_admin_url( Addons::NAME );
	}

	public function register() {
		add_filter( 'wp_redirect', [ $this, 'redirect_after_status_change' ] );
	}

	/**
	 * @param string $basename
	 *
	 * @return Integration|null
	 */
	private function get_integration_by_basename( $basename ) {
		/** @var Integration $integration */
		foreach ( $this->integrations as $integration ) {
			if ( $integration->get_basename() === $basename ) {
				return $integration;
			}
		}

		return null;
	}

	/**
	 * Redirect the user to the Admin Columns add-ons page after activation/deactivation of an add-on from the add-ons page
	 *
	 * @param string $location
	 *
	 * @return string
	 * @since 2.2
	 */
	public function redirect_after_status_change( $location ) {
		global $pagenow;

		if ( 'plugins.php' !== $pagenow || ! filter_input( INPUT_GET, 'ac-redirect' ) || filter_input( INPUT_GET, 'error' ) ) {
			return $location;
		}

		$status = filter_input( INPUT_GET, 'action' );

		if ( ! $status ) {
			return $location;
		}

		$integration = $this->get_integration_by_basename( filter_input( INPUT_GET, 'plugin' ) );

		if ( ! $integration ) {
			return $location;
		}

		$location = add_query_arg( [
			'status'    => $status,
			'plugin'    => $integration->get_slug(),
			'_ac_nonce' => wp_create_nonce( 'ac-plugin-status-change' ),
		], $this->get_url() );

		return $location;
	}

}