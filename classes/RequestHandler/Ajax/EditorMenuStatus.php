<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Storage;

class EditorMenuStatus implements RequestAjaxHandler
{

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

        $preference = new Storage\Model\EditorMenuStatus();

        $preference->save_status(
            $request->get('group'),
            'open' === $request->get('status')
        );

        $response->success();
    }

}