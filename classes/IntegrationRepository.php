<?php

namespace AC;

use AC\Integration\Filter;

class IntegrationRepository
{

    public function find_all(): Integrations
    {
        static $integrations = null;

        if (null === $integrations) {
            $integrations = new Integrations([
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

        return $integrations;
    }

    public function find_by_slug(string $slug): ?Integration
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
        return (new Filter\IsActive())->filter($this->find_all());
    }

    public function find_all_by_active_plugins(): Integrations
    {
        return (new Filter\IsPluginActive())->filter($this->find_all());
    }

    public function find_all_by_inactive_plugins(): Integrations
    {
        return (new Filter\IsPluginNotActive())->filter($this->find_all());
    }

}