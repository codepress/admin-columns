<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Capabilities;
use AC\RequestAjaxHandler;

class Integrations implements RequestAjaxHandler
{

    private AC\IntegrationRepository $integrations;

    public function __construct(
        AC\IntegrationRepository $integrations
    ) {
        $this->integrations = $integrations;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $response = new AC\Response\Json();

        $integrations = [];

        foreach ($this->integrations->find_all() as $integration) {
            /* @var AC\Integration $integration */
            $integrations[] = [
                'plugin_active' => $integration->is_plugin_active(),
                'title'         => $integration->get_title(),
                'external_link' => $integration->get_link(),
                'slug'          => $integration->get_slug(),
                'description'   => $integration->get_description(),
                'plugin_logo'   => $integration->get_logo(),
                'plugin_link'   => $integration->get_plugin_link(),
            ];
        }

        $response->set_parameter('integrations', $integrations);

        $response->success();
    }

}