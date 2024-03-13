<?php

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;

class TestComponent implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        return (new ComponentBuilder())->build();
    }

}