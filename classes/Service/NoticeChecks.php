<?php

namespace AC\Service;

use AC\AdminColumns;
use AC\Capabilities;
use AC\Check;
use AC\Integration\IntegrationRepository;
use AC\Notice\DismissRegistry;
use AC\Preferences\UserFactory;
use AC\Registerable;
use AC\Services;

class NoticeChecks implements Registerable
{

    private IntegrationRepository $integration_repository;

    private AdminColumns $plugin;

    private UserFactory $preferences_factory;

    private DismissRegistry $dismiss_registry;

    public function __construct(AdminColumns $plugin, IntegrationRepository $integration_repository, UserFactory $preferences_factory, DismissRegistry $dismiss_registry)
    {
        $this->plugin = $plugin;
        $this->integration_repository = $integration_repository;
        $this->preferences_factory = $preferences_factory;
        $this->dismiss_registry = $dismiss_registry;
    }

    public function register(): void
    {
        $this->create_services()->register();
    }

    private function create_services(): Services
    {
        $services = new Services();

        if (current_user_can(Capabilities::MANAGE)) {
            $services->add(new Check\Review($this->plugin->get_location(), $this->preferences_factory, $this->dismiss_registry));

            foreach ($this->integration_repository->find_all_by_active_plugins() as $integration) {
                $services->add(new Check\AddonAvailable($integration, $this->preferences_factory, $this->dismiss_registry));
            }
        }

        return $services;
    }

}