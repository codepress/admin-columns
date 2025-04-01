<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Id;

class UserIdFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('User ID', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_id';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Id());

        return $formatters;
    }

}