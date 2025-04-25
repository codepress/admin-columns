<?php

declare(strict_types=1);

namespace AC\Setting\ContextFactory;

use AC;
use AC\Column;
use AC\Setting\ConditionalContextFactory;
use AC\Setting\ConfigFactory;
use AC\Setting\Context;
use AC\TableScreen;

class CustomField implements ConditionalContextFactory
{

    private ConfigFactory $factory;

    public function __construct(ConfigFactory $factory)
    {
        $this->factory = $factory;
    }

    public function create(AC\Column $column, AC\TableScreen $table_screen): Context
    {
        $config = $this->factory->create($column);

        return new Context\CustomField(
            $this->factory->create($column),
            $config->get('field_type', ''),
            $config->get('meta_key', ''),
        );
    }

    public function supports(Column $column, TableScreen $table_screen): bool
    {
        return $column->get_type() === 'column-meta';
    }
}