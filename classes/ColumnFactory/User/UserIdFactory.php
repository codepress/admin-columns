<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;

class UserIdFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('User ID', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_id';
    }

}