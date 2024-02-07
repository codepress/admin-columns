<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;

class WidthFactory
{

    // TODO David
    public function create(Config $config, Specification $specification = null)
    {
        return new Width(
            $config->has('width') ? (int)$config->get('width') : null,
            $config->get('width_unit')
        );
    }

}