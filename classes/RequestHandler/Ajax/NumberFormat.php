<?php

namespace AC\RequestHandler\Ajax;

use AC\Request;
use AC\RequestAjaxHandler;

final class NumberFormat implements RequestAjaxHandler
{

    public function handle(): void
    {
        $request = new Request();

        $number = $request->get('number') ?: 7500;
        $decimals = $request->get('decimals') ?: null;
        $decimal_separator = $request->get('decimal_point') ?: null;
        $thousands_separator = $request->get('thousands_sep') ?: '';

        wp_send_json_success(
            number_format($number, $decimals, $decimal_separator, $thousands_separator)
        );
    }

}