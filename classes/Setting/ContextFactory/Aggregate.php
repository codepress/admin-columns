<?php

declare(strict_types=1);

namespace AC\Setting\ContextFactory;

use AC\Column;
use AC\Setting\Context;
use AC\Setting\ContextFactory;

final class Aggregate implements ContextFactory
{

    private array $factories;

    private ContextFactory $default;

    public function __construct(ContextFactory $default)
    {
        $this->default = $default;
    }

    public function add(string $group, ContextFactory $contextFactory): void
    {
        $this->factories[$group] = $contextFactory;
    }

    public function create(Column $column): Context
    {
        return ($this->factories[$column->get_group()] ?? $this->default)->create($column);
    }

}