<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class UserUrlFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Website', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_url';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->add(new Formatter\User\Property('user_url'))
                     ->add(new Formatter\Linkable(null, '_blank'));
    }

}