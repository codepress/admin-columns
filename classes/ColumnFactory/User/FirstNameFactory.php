<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class FirstNameFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('First Name', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-first_name';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->add(new Formatter\User\Property('first_name'));
    }

}