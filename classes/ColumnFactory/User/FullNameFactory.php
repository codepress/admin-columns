<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class FullNameFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Full Name', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_fullname';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\User\Property('full_name'));

        return parent::get_formatters($components, $config, $formatters);
    }

}