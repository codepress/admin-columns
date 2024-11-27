<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Storage\Repository\IntegrationStatus;

class IntegrationToggle implements RequestAjaxHandler
{

    private IntegrationStatus $repository;

    public function __construct(IntegrationStatus $repository)
    {
        $this->repository = $repository;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            wp_send_json_error();
        }

        $request = new Request();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            wp_send_json_error();
        }

        $integration = $request->get('integration');

        if ( ! $integration) {
            wp_send_json_error();
        }

        $is_active = $request->get('status') === 'true';

        $is_active
            ? $this->repository->set_active($integration)
            : $this->repository->set_inactive($integration);

        wp_send_json_success();
    }

}