<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Id;

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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Id());
    }

}