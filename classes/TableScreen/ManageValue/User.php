<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\Registerable;
use DomainException;

class User implements Registerable
{

    private GridRenderable $renderable;

    private int $priority;

    public function __construct(
        GridRenderable $renderable,
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

    public function render_value($value, $column_id, $row_id): ?string
    {
        return $this->renderable->render($column_id, $row_id) ?? $value;
    }

}