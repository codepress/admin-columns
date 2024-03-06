<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\AttributeCollection;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Type\Attribute;

final class Name implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        return new Component(
            null,
            null,
            new Input(
                'name',
                'hidden',
                (string)$config->get('name')
            ),
            $conditions,
            null,
            null,
            new AttributeCollection([
                new Attribute('component', 'hidden'),
            ])
        );
    }

}