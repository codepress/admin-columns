<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;

class LastNameFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Last Name', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-last_name';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->prepend(new Formatter\User\Meta('last_name'));
    }

}