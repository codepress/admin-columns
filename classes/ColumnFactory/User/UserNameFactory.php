<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;

class UserNameFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Username', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_login';
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new Formatter\User\UserName());
    }

}