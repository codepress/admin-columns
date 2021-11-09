<?php

namespace AC\Integration\Filter;

use AC\Integration;
use AC\Integration\Filter;
use AC\Integrations;
use ACP\Type\Activation\Products;

class ContainsProducts implements Filter {

	/**
	 * @var Products
	 */
	private $products;

	public function __construct( Products $products ) {
		$this->products = $products;
	}

	public function filter( Integrations $integrations ) {
		return new Integrations( array_filter( $integrations->all(), [ $this, 'has_product' ] ) );
	}

	/**
	 * @param Integration $integration
	 *
	 * @return bool
	 */
	private function has_product( Integration $integration ) {
		return in_array( $integration->get_slug(), $this->products->get_value(), true );
	}

}