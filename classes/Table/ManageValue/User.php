<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Registerable;
use AC\Table\Renderable;
use AC\Type\ColumnId;
use DomainException;

class User implements Registerable
{

    private ColumnId $column_id;

    private Renderable $renderable;

    private int $priority;

    public function __construct(
        ColumnId $column_id,
        Renderable $renderable,
        int $priority = 100
    ) {
        $this->column_id = $column_id;
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

    public function render_value($value, $column_id, $row_id): ?string
    {
        if ((string)$this->column_id !== (string)$column_id) {
            return (string)$value;
        }

        return $this->renderable->render($row_id) ?? $value;
    }

}