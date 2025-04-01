<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class DisplayNameFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Display Name', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-display_name';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Formatter\User\Property('display_name'));

        return $formatters;
    }

}