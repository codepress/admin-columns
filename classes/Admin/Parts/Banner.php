<?php

namespace AC\Admin\Parts;

use AC\Admin;
use AC\Autoloader;
use AC\Integrations;
use AC\PluginInformation;
use AC\View;

class Banner {

	/**
	 * @return Admin\Promo|false
	 */
	private function get_active_promotion() {
		$classes = Autoloader::instance()->get_class_names_from_dir( 'AC\Admin\Promo' );

		foreach ( $classes as $class ) {

			/* @var Admin\Promo $promo */
			$promo = new $class;

			if ( $promo->is_active() ) {
				return $promo;
			}
		}

		return false;
	}

	/**
	 * @return int
	 */
	private function get_discount_percentage() {
		return 10;
	}

	/**
	 * @return \AC\Integration[]
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
			'price'        => ac_get_lowest_price(),
		) );

		$banner->set_template( 'admin/side-banner' );

		return $banner->render();
	}

	public function __toString() {
		return $this->render();
	}

}