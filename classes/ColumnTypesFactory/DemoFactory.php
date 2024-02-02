<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC\Column;
use AC\Setting\Config;
use AC\TableScreen;

// TODO Proof-of-concept POC
class DemoFactory
{

    private $factory;

    public function __construct(DemoTypesFactory $factory)
    {
        $this->factory = $factory;
    }

    public function create(TableScreen $table_screen, string $type, Config $config): ?Column
    {
        $factory = $this->factory->create($table_screen)[$type] ?? null;

        return $factory
            ? $factory->create($config)
            : null;
    }

}