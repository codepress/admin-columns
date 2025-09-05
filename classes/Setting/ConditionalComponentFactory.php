<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\Specification;

final class ConditionalComponentFactory
{

    private $factory;

    private $conditions;

    public function __construct(ComponentFactory $factory, ?Specification $conditions = null)
    {
        $this->factory = $factory;
        $this->conditions = $conditions;
    }

    public function create(Config $config): Component
    {
        return $this->factory->create($config, $this->conditions);
    }

}