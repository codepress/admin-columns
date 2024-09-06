<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
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

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Id());

        return parent::get_formatters($components, $config, $formatters);
    }

}