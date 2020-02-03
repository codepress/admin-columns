<?php

namespace AC\Admin\Parts;

use AC\Admin;
use AC\Integration;
use AC\Integrations;
use AC\PluginInformation;
use AC\View;

class Banner {

	/**
	 * @return Admin\Promo|null
	 */
	private function get_active_promotion() {
		$promos = new Admin\PromoCollection();

		return $promos->find_active();
	}

	/**
	 * @return int
	 */
	private function get_discount_percentage() {
		return 10;
	}

	/**
	 * @return Integration[]
	 */
	private function get_missing_integrations() {
		$missing = array();

		foreach ( new Integrations() as $integration ) {
			$integration_plugin = new PluginInformation( $integration->get_basename() );

			if ( $integration->is_plugin_active() && ! $integration_plugin->is_active() ) {
				$missing[] = $integration;
			}
		}

		return $missing;
	}

	/**
	 * @return string
	 */
	public function render() {
		$banner = new View( array(
			'promo'        => $this->get_active_promotion(),
			'integrations' => $this->get_missing_integrations(),
			'discount'     => $this->get_discount_percentage(),
		) );

		$banner->set_template( 'admin/side-banner' );

		return $banner->render();
	}

	public function __toString() {
		return $this->render();
	}

}