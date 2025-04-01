<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class AuthorSlugFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Author Slug', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_nicename';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Formatter\User\Property('user_nicename'));
        $formatters->add(new Formatter\User\AuthorPostUrl());

        return $formatters;
    }

}