<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\Table\ManageValue\RenderFactory;
use AC\TableScreen\ManageValueService;
use AC\Type\ColumnId;
use AC\Type\Value;
use DomainException;

class User implements ManageValueService
{

    private RenderFactory $factory;

    private int $priority;

    public function __construct(RenderFactory $factory, int $priority = 100)
    {
        $this->factory = $factory;
        $this->priority = $priority;
    }

    public function register(): void
    {
        if (function_exists('did_filter') && did_filter('manage_users_custom_column')) {
            throw new DomainException("Method should be called before the filter triggers.");
        }

        add_filter('manage_users_custom_column', [$this, 'render_value'], $this->priority, 3);
    }

    public function render_value(...$args)
    {
        [$value, $column_id, $row_id] = $args;

        $formatter = $this->factory->create(new ColumnId((string)$column_id));

        return $formatter
            ? (string)$formatter->format(new Value((int)$row_id))
            : $value;
    }

}