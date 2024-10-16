<?php

declare(strict_types=1);

namespace AC\Setting\ContextFactory;

use AC;
use AC\Setting\Context;
use AC\Setting\ContextFactory;

class Column implements ContextFactory
{

    private AC\Setting\ConfigFactory $factory;

    public function __construct(AC\Setting\ConfigFactory $factory)
    {
        $this->factory = $factory;
    }

    public function create(AC\Column $column): Context
    {
        return new Context($this->factory->create($column));
    }

}