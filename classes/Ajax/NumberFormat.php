<?php

namespace AC\Ajax;

use AC;

final class NumberFormat implements AC\Registrable {

	/**
	 * @var AC\Request
	 */
	private $request;

	public function __construct( $request ) {
		$this->request = $request;
	}

	public function register() {
		add_action( 'wp_ajax_ac_number_format', [ $this, 'request' ] );
	}

	public function request() {
		$number = $this->request->get( 'number' ) ?: 7500;
		$decimals = $this->request->get( 'decimals' ) ?: null;
		$decimal_point = $this->request->get( 'decimal_point' ) ?: null;
		$thousands_sep = $this->request->get( 'thousands_sep' ) ?: '';

		echo number_format( $number, $decimals, $decimal_point, $thousands_sep );
		exit;
	}

}