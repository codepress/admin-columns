<?php

namespace AC\Admin;

use AC\Integration;
use AC\IntegrationRepository;
use AC\Integrations;
use AC\Promo;
use AC\PromoCollection;
use AC\View;

class Banner {

	/**
	 * @var IntegrationRepository
	 */
	private $integrations;

	public function __construct() {
		$this->integrations = new IntegrationRepository();
	}

	private function get_active_promotion(): ?Promo {
		return ( new PromoCollection() )->find_active();
	}

	private function get_missing_integrations(): Integrations {
		return $this->integrations->find_all( [
			'filter' => [
				new Integration\Filter\IsPluginActive(),
			],
		] );
	}

	/**
	 * @return string
	 */
	public function render() {
		$banner = new View( [
			'promo'        => $this->get_active_promotion(),
			'integrations' => $this->get_missing_integrations()->all(),
			'discount'     => 10,
		] );

		$banner->set_template( 'admin/side-banner' );

		return $banner->render();
	}

	public function __toString() {
		return $this->render();
	}

}