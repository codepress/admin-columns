<?php

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

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
        return parent::get_formatters($config)
                     ->add(new AC\Formatter\User\FullName());
    }

}