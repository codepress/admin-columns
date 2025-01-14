<?php

namespace AC\Service;

use AC\AdminColumns;
use AC\Capabilities;
use AC\Check;
use AC\Integration\IntegrationRepository;
use AC\Registerable;
use AC\Services;

class NoticeChecks implements Registerable
{

    private $location;

    private IntegrationRepository $integration_repository;

    public function __construct(AdminColumns $plugin, IntegrationRepository $integration_repository)
    {
        $this->location = $plugin->get_location();
        $this->integration_repository = $integration_repository;
    }

    public function register(): void
    {
        $this->create_services()->register();
    }

    private function create_services(): Services
    {
        $services = new Services();

        if (current_user_can(Capabilities::MANAGE)) {
            $services->add(new Check\Review($this->location));

            foreach ($this->integration_repository->find_all_by_active_plugins() as $integration) {
                $services->add(new Check\AddonAvailable($integration));
            }
        }

        return $services;
    }

}