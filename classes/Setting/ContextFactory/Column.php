<?php

declare(strict_types=1);

namespace AC\Setting\ContextFactory;

use AC;
use AC\Setting\ConfigFactory;
use AC\Setting\Context;
use AC\Setting\ContextFactory;
use AC\TableScreen;

class Column implements ContextFactory
{

    private ConfigFactory $factory;

    public function __construct(ConfigFactory $factory)
    {
        $this->factory = $factory;
    }

    public function create(AC\Column $column, TableScreen $table_screen): Context
    {
        return new Context(
            $this->factory->create($column)
        );
    }

}