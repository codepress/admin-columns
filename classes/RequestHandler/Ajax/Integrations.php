<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Capabilities;
use AC\Integration\IntegrationRepository;
use AC\RequestAjaxHandler;
use AC\Response\Json;

class Integrations implements RequestAjaxHandler
{

    private IntegrationRepository $integrations;

    private AC\Storage\Repository\IntegrationStatus $status;

    public function __construct(
        IntegrationRepository $integrations,
        AC\Storage\Repository\IntegrationStatus $status
    ) {
        $this->integrations = $integrations;
        $this->status = $status;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $response = new Json();

        $integrations = [];

        foreach ($this->integrations->find_all() as $integration) {
            /**
             * @var AC\Integration $integration
             */
            $integrations[] = [
                'plugin_active' => $integration->is_plugin_active(),
                'title'         => $integration->get_title(),
                'external_link' => $integration->get_link(),
                'slug'          => $integration->get_slug(),
                'description'   => $integration->get_description(),
                'plugin_logo'   => $integration->get_logo(),
                'plugin_link'   => $integration->get_plugin_link(),
                'active'        => $this->status->is_active($integration->get_slug()),
            ];
        }

        $response->set_parameter('integrations', $integrations);

        $response->success();
    }

}