<?php

namespace AC\Integration\Filter;

use AC\Integration;
use AC\Integration\Filter;
use AC\Type\Integrations;

class IsPluginNotActive implements Filter
{

    public function filter(Integrations $integrations): Integrations
    {
        return new Integrations(array_filter($integrations->all(), [$this, 'is_not_active']));
    }

    private function is_not_active(Integration $integration): bool
    {
        return ! $integration->is_plugin_active();
    }

}