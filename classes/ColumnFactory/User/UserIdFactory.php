<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;

class UserIdFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('User ID', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_id';
    }

}