<?php

declare(strict_types=1);

namespace AC\Integration\Filter;

use AC\Integration;
use AC\Integration\Filter;
use AC\Integrations;

class IsActive implements Filter
{

    public function filter(Integrations $integrations): Integrations
    {
        return new Integrations(array_filter($integrations->all(), [$this, 'is_active']));
    }

    private function is_active(Integration $integration): bool
    {
        return (bool)apply_filters('ac/integration/active', false, $integration);
    }

}