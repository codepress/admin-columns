<?php

namespace AC\Service;

use AC\Asset\Location\Absolute;
use AC\Capabilities;
use AC\Check;
use AC\Integration\IntegrationRepository;
use AC\Registerable;
use AC\Services;

class NoticeChecks implements Registerable
{

    private $location;

    private $integration_repository;

    public function __construct(Absolute $location, IntegrationRepository $integration_repository)
    {
        $this->location = $location;
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