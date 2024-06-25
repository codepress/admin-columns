<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Table\ColumnRenderable;
use AC\Table\ManageValue;
use DomainException;

class User extends ManageValue
{

    private $renderable;

    public function __construct(ColumnRenderable $renderable)
    {
        $this->renderable = $renderable;
    }

    public function register(): void
    {
        if (function_exists('did_filter') && did_filter('manage_users_custom_column')) {
            throw new DomainException("Method should be called before the filter triggers.");
        }

        add_filter('manage_users_custom_column', [$this, 'render_value'], 100, 3);
    }

    public function render_value($value, $column_name, $id): ?string
    {
        return $this->renderable->render((string)$column_name, (int)$id) ?? (string)$value;
    }
}