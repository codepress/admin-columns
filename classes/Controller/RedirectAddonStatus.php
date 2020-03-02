<?php

namespace AC\Controller;

use AC\IntegrationFactory;
use AC\Registrable;

class RedirectAddonStatus implements Registrable {

	/**
	 * @var string
	 */
	private $url;

	public function __construct( $url ) {
		$this->url = $url;
	}

	public function register() {
		add_filter( 'wp_redirect', [ $this, 'redirect_after_status_change' ] );
	}

	/**
	 * Redirect the user to the Admin Columns add-ons page after activation/deactivation of an add-on from the add-ons page
	 *
	 * @param $location
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

		$integration = IntegrationFactory::create( filter_input( INPUT_GET, 'plugin' ) );

		if ( ! $integration ) {
			return $location;
		}

		$location = add_query_arg( [
			'status'    => $status,
			'plugin'    => $integration->get_slug(),
			'_ac_nonce' => wp_create_nonce( 'ac-plugin-status-change' ),
		], $this->url );

		return $location;
	}

}