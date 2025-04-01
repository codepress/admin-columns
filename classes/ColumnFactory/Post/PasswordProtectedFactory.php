<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\IsPasswordProtected;

class PasswordProtectedFactory extends ColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-password_protected';
    }

    public function get_label(): string
    {
        return __('Password Protected', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new IsPasswordProtected());

        return $formatters;
    }

}