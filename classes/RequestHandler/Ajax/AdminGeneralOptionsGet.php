<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Settings\GeneralOptionFactory;

class AdminGeneralOptionsGet implements RequestAjaxHandler
{

    private GeneralOptionFactory $option_factory;

    public function __construct(GeneralOptionFactory $option_factory)
    {
        $this->option_factory = $option_factory;
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

        $value = $this->option_factory->create()
                                      ->get($name);

        wp_send_json_success($value);
    }

}