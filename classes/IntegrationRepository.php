<?php

namespace AC;

use AC\Integration\Filter;

class IntegrationRepository
{

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