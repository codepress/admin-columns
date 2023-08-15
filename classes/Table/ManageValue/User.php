<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Table\ManageValue;
use DomainException;

class User extends ManageValue
{

    public function register(): void
    {
        if (function_exists('did_filter') && did_filter('manage_users_custom_column')) {
            throw new DomainException("Method should be called before the filter triggers.");
        }

        add_filter('manage_users_custom_column', [$this, 'render_value'], 100, 3);
    }

    public function render_value($value, $column_name, $user_id): ?string
    {
        return $this->render_cell((string)$column_name, (int)$user_id, (string)$value);
    }
}