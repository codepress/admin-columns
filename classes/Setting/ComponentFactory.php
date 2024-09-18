<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\Specification;

interface ComponentFactory
{

    // TODO check which implementation is necessary
    public function create(Config $config, Specification $conditions = null): Component;

}