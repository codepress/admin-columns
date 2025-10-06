<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Settings\GeneralOption;

class AdminGeneralOptionsGet implements RequestAjaxHandler
{

    private GeneralOption $option_storage;

    public function __construct(GeneralOption $option_storage)
    {
        $this->option_storage = $option_storage;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $name = (string)$request->filter('option_name');

        $value = $this->option_storage->get($name);

        wp_send_json_success($value);
    }

}