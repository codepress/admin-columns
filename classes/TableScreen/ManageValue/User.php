<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\Table\ManageValue\ValueFormatter;
use AC\TableScreen\ManageValueService;
use AC\Type\ColumnId;
use AC\Type\Value;
use DomainException;

class User implements ManageValueService
{

    private ValueFormatter $formatter;

    private int $priority;

    public function __construct(
        ValueFormatter $formatter,
        int $priority = 100
    ) {
        $this->formatter = $formatter;
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

        // TODO test default_value
        return (string)$this->formatter->format(
            new ColumnId((string)$column_id),
            new Value((int)$row_id, $value)
        );
    }

}