<?php

declare(strict_types=1);

namespace AC\Setting\ContextFactory;

use AC;
use AC\Setting\ConfigFactory;
use AC\Setting\Context;
use AC\Setting\ContextFactory;

class Column implements ContextFactory
{

    private ConfigFactory $factory;

    public function __construct(ConfigFactory $factory)
    {
        $this->factory = $factory;
    }

    public function create(AC\Column $column): Context
    {
        return new Context(
            $this->factory->create($column)
        );
    }

}