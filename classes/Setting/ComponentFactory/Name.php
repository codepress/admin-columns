<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control;
use AC\Setting\Control\Input;

class Name implements ComponentFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new Component(
            'Name',
            null,
            new Control(
                new Input(
                    'name',
                    'hidden',
                    (string)$config->get('name')
                ),
                null
            )
        );
    }

}