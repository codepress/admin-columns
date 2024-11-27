<?php

namespace AC;

use AC\Integration\Filter;
use AC\Settings\GeneralOption;
use AC\Settings\GeneralOptionFactory;

class IntegrationRepository
{

    private GeneralOptionFactory $general_option_factory;

    public function __construct(GeneralOptionFactory $general_option_factory)
    {
        $this->general_option_factory = $general_option_factory;
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

    private function get_storge(): GeneralOption
    {
        return $this->general_option_factory->create();
    }

    public function save_status(Integration $integration, bool $status): void
    {
        $integrations = $this->get_storge()->get('intergrations') ?: [];

        $integrations[$integration->get_slug()] = $status;

        $this->get_storge()->save('intergrations', $integrations);
    }

    private function is_active(string $slug): bool
    {
        $status = $this->get_storge()->get('intergrations')[$slug] ?? null;

        return $status !== '0';
    }

    public function find_all_active(): Integrations
    {
        $integrations = new Integrations();

        foreach ($this->find_all() as $integration) {
            $is_active = $this->is_active($integration->get_slug());
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

    public function find_all_by_inactive_plugins(): Integrations
    {
        return (new Filter\IsPluginNotActive())->filter($this->find_all());
    }

}