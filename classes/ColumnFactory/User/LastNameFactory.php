<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class LastNameFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('Last Name', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-last_name';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\User\Meta('last_name'));

        return parent::get_formatters($components, $config, $formatters);
    }

}