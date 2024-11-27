<?php

namespace AC\Integration;

use AC\Integration;
use AC\Storage\Repository\IntegrationStatus;
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
            new Integration\ACF(),
            new Integration\BuddyPress(),
            new Integration\EventsCalendar(),
            new Integration\GravityForms(),
            new Integration\JetEngine(),
            new Integration\Pods(),
            new Integration\Types(),
            new Integration\MetaBox(),
            new Integration\MediaLibraryAssistant(),
            new Integration\WooCommerce(),
            new Integration\YoastSeo(),
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

    public function find_all_active(): Integrations
    {
        $integrations = new Integrations();

        foreach ($this->find_all() as $integration) {
            $is_active = $this->storage->is_status_active($integration->get_slug());
            $is_active = (bool)apply_filters('ac/integration/active', $is_active, $integration);

            if ($is_active) {
                $integrations->add($integration);
            }
        }

        return $integrations;
    }

    public function find_all_by_active_plugins(): Integrations
    {
        return (new Filter\IsPluginActive())->filter($this->find_all());
    }

}