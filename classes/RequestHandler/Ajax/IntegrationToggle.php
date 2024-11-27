<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\IntegrationRepository;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;

class IntegrationToggle implements RequestAjaxHandler
{

    private IntegrationRepository $repository;

    public function __construct(IntegrationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(): void
    {
        $request = new Request();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            wp_send_json_error();
        }

        $integration = $this->repository->find(
            $request->get('integration', '')
        );

        if ( ! $integration) {
            wp_send_json_error();
        }

        $this->repository->save_status(
            $integration,
            (bool)$request->get('status')
        );

        wp_send_json_success();
    }

}