<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Formatter\User\Property('user_nicename'));
        $formatters->add(new Formatter\User\AuthorPostUrl());
    }

}