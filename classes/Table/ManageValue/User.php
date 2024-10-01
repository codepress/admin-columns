<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Column;
use AC\Registerable;
use AC\Table\ColumnRenderable;
use DomainException;

// TODO Proof-of-concept
class User implements Registerable
{

    private Column $column;

    private int $priority;

    public function __construct(Column $column, int $priority = 100)
    {
        $this->column = $column;
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
        if ((string)$this->column->get_id() !== (string)$column_id) {
            return (string)$value;
        }

        return ColumnRenderable::render($this->column, (int)$row_id);
    }

}