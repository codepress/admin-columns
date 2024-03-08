<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input;

final class Name implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        return (new ComponentBuilder())
            ->set_input(
                new Input(
                    'name',
                    'hidden',
                    (string)$config->get('name')
                )
            )
            ->set_type('hidden')
            ->build();
    }

}