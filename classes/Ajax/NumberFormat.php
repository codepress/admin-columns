<?php

namespace AC\Ajax;

use AC;
use AC\Request;

final class NumberFormat implements AC\Registerable {

	public function register(): void
    {
		add_action( 'wp_ajax_ac_number_format', [ $this, 'request' ] );
	}

	public function request() {
		$request = new Request();

		$number = $request->get( 'number' ) ?: 7500;
		$decimals = $request->get( 'decimals' ) ?: null;
		$decimal_point = $request->get( 'decimal_point' ) ?: null;
		$thousands_sep = $request->get( 'thousands_sep' ) ?: '';

		wp_send_json_success( number_format( $number, $decimals, $decimal_point, $thousands_sep ) );
	}

}