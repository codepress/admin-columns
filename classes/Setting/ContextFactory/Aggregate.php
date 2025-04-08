<?php

declare(strict_types=1);

namespace AC\Setting\ContextFactory;

use AC\Column;
use AC\Setting\ConditionalContextFactory;
use AC\Setting\Context;
use AC\Setting\ContextFactory;

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

    public function create(Column $column): Context
    {
        echo '<pre>';
        print_r($this->factories);
        echo '</pre>';
        foreach ($this->factories as $factory) {
            if ($factory->supports($column)) {
                $factory->create($column);
            }
        }

        return $this->default->create($column);
    }

}