<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class DisplayNameFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('Display Name', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-display_name';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\User\Property('display_name'));

        return parent::get_formatters($components, $config, $formatters);
    }

}