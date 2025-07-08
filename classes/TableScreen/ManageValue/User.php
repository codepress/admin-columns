<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\CellRenderer;
use AC\TableScreen\ManageValueService;
use DomainException;

class User implements ManageValueService
{

    private CellRenderer $renderable;

    private int $priority;

    public function __construct(
        CellRenderer $renderable,
        int $priority = 100
    ) {
        $this->renderable = $renderable;
        $this->priority = $priority;
    }

    public function register(): void
    {
        if (function_exists('did_filter') && did_filter('manage_users_custom_column')) {
            throw new DomainException("Method should be called before the filter triggers.");
        }

        add_filter('manage_users_custom_column', [$this, 'render_value'], $this->priority, 3);
    }

    public function render_value(...$args): ?string
    {
        [$value, $column_id, $row_id] = $args;

        return $this->renderable->render_cell((string)$column_id, $row_id) ?? $value;
    }

}