<?php

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class AuthorSlugFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Author Slug', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_nicename';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AC\Value\Formatter\User\Property('user_nicename'));
        $formatters->add(new AC\Value\Formatter\User\AuthorPostUrl());

        return parent::get_formatters($components, $config, $formatters);
    }

}