<?php

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

class UserNameFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Username', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_login';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AC\Formatter\User\UserName());

        return $formatters;
    }

}