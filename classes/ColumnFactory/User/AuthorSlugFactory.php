<?php

declare(strict_types=1);

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AC\Formatter\User\Property('user_nicename'));
        $formatters->add(new AC\Formatter\User\AuthorPostUrl());

        return $formatters;
    }

}