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

    public function __construct(IntegrationRepository $integrations)
    {
        $this->integrations = $integrations;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $response = new Json();

        $integrations = [];

        foreach ($this->integrations->find_all_active() as $integration) {
            $integrations[] = $this->encode($integration, true);
        }

        foreach ($this->integrations->find_all_inactive() as $integration) {
            $integrations[] = $this->encode($integration, false);
        }

        $response->set_parameter('integrations', $integrations);

        $response->success();
    }

    private function encode(AC\Type\Integration $integration, bool $active): array
    {
        return [
            'plugin_active' => $integration->is_plugin_active(),
            'title'         => $integration->get_title(),
            'external_link' => $integration->get_link(),
            'slug'          => $integration->get_slug(),
            'description'   => $integration->get_description(),
            'plugin_logo'   => $integration->get_logo(),
            'plugin_link'   => $integration->get_plugin_link(),
            'active'        => $active,
        ];
    }

}