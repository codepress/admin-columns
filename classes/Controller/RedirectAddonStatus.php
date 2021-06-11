<?php

namespace AC\Controller;

use AC\Admin\AddonStatus;
use AC\Admin\Main\Addons;
use AC\Integration;
use AC\Integrations;
use AC\Registrable;

class RedirectAddonStatus implements Registrable {

	/**
	 * @var Integrations
	 */
	private $integrations;

	public function __construct( Integrations $integrations ) {
		$this->integrations = $integrations;
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

		$redirect_to = filter_input( INPUT_GET, AddonStatus::REDIRECT_PARAM );

		if ( 'plugins.php' !== $pagenow || ! $redirect_to || filter_input( INPUT_GET, 'error' ) ) {
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

		$url = AddonStatus::REDIRECT_TO_NETWORK == $redirect_to
			? ac_get_admin_network_url( Addons::NAME )
			: ac_get_admin_url( Addons::NAME );

		$location = add_query_arg( [
			'status'    => $status,
			'plugin'    => $integration->get_slug(),
			'_ac_nonce' => wp_create_nonce( 'ac-plugin-status-change' ),
		], $url );

		return $location;
	}

}