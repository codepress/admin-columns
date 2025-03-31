<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class FullNameFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Full Name', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_fullname';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Formatter\User\Property('full_name'));

        return $formatters;
    }

}