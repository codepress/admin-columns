<?php

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class LastNameFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Last Name', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-last_name';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AC\Value\Formatter\User\Meta('last_name'));

        return parent::get_formatters($components, $config, $formatters);
    }

}