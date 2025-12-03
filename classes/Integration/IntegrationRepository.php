<?php

namespace AC\Integration;

use AC\Storage\Repository\IntegrationStatus;
use AC\Type\Integration;
use AC\Type\Integrations;

class IntegrationRepository
{

    private IntegrationStatus $storage;

    public function __construct(IntegrationStatus $storage)
    {
        $this->storage = $storage;
    }

    public function find_all(): Integrations
    {
        return new Integrations([
            new ACF(),
            new BuddyPress(),
            new EventsCalendar(),
            new GravityForms(),
            new JetEngine(),
            new Pods(),
            new Types(),
            new MetaBox(),
            new MediaLibraryAssistant(),
            new RankMath(),
            new SeoPress(),
            new WooCommerce(),
            new YoastSeo(),
        ]);
    }

    public function find(string $slug): ?Integration
    {
        foreach ($this->find_all() as $integration) {
            if ($integration->get_slug() === $slug) {
                return $integration;
            }
        }

        return null;
    }

    public function find_all_inactive(): Integrations
    {
        $integrations = new Integrations();

        foreach ($this->find_all() as $integration) {
            if ( ! $this->is_active($integration)) {
                $integrations->add($integration);
            }
        }

        return $integrations;
    }

    public function find_all_active(): Integrations
    {
        $integrations = new Integrations();

        foreach ($this->find_all() as $integration) {
            if ($this->is_active($integration)) {
                $integrations->add($integration);
            }
        }

        return $integrations;
    }

    public function find_all_by_active_plugins(): Integrations
    {
        $integrations = new Integrations();

        foreach ($this->find_all() as $integration) {
            if ($integration->is_plugin_active()) {
                $integrations->add($integration);
            }
        }

        return $integrations;
    }

    private function is_enabled(Integration $integration): bool
    {
        return $this->storage->is_active($integration->get_slug());
    }

    private function is_integration_active(Integration $integration): bool
    {
        return (bool)apply_filters('ac/integration/active', false, $integration);
    }

    private function is_active(Integration $integration): bool
    {
        return $this->is_enabled($integration) && $this->is_integration_active($integration);
    }

}