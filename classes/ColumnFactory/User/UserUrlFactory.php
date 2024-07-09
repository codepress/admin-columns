<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
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

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\User\Property('user_url'));
        $formatters->add(new Formatter\Linkable(null, '_blank'));

        return parent::get_formatters($components, $config, $formatters);
    }

}