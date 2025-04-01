<?php

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
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

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->add(new AC\Value\Formatter\User\Meta('last_name'));
    }

}