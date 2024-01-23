<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Admin;
use AC\Capabilities;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response;

class ScreenOptions implements RequestAjaxHandler
{

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Response\Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $name = (string)filter_input(INPUT_POST, 'option_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $active = 1 === (int)filter_input(INPUT_POST, 'option_value', FILTER_SANITIZE_NUMBER_INT);

        (new Admin\Preference\ScreenOptions())->set_status(
            $name,
            $active
        );
    }

}