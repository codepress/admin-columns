<?php

declare(strict_types=1);

namespace AC\Setting\ContextFactory;

use AC\Column;
use AC\Setting\ConditionalContextFactory;
use AC\Setting\Context;
use AC\Setting\ContextFactory;
use AC\TableScreen;

final class Aggregate implements ContextFactory
{

    private int $added = 0;

    /**
     * @var ConditionalContextFactory[]
     */
    private array $factories = [];

    private ContextFactory $default;

    public function __construct(ContextFactory $default)
    {
        $this->default = $default;
    }

    public function add(ConditionalContextFactory $contextFactory, int $priority = 10): void
    {
        $this->factories[$priority . '.' . $this->added++] = $contextFactory;

        ksort($this->factories, SORT_NATURAL);
    }

    public function create(Column $column, TableScreen $table_screen): Context
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($column, $table_screen)) {
                return $factory->create($column, $table_screen);
            }
        }

        return $this->default->create($column, $table_screen);
    }

}