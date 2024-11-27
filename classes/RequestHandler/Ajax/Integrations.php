<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Capabilities;
use AC\IntegrationRepository;
use AC\RequestAjaxHandler;

class Integrations implements RequestAjaxHandler
{

    private IntegrationRepository $integrations;

    public function __construct(
        IntegrationRepository $integrations
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

        $active_integrations = $this->get_active_slugs();

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
                'active'        => in_array($integration->get_slug(), $active_integrations, true),
            ];
        }

        $response->set_parameter('integrations', $integrations);

        $response->success();
    }

    private function get_active_slugs(): array
    {
        $slugs = [];

        foreach ($this->integrations->find_all_active() as $integration) {
            $slugs[] = $integration->get_slug();
        }

        return $slugs;
    }

}