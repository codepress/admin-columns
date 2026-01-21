<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Post\IsPasswordProtected;
use AC\FormatterCollection;
use AC\Setting\Config;

class PasswordProtectedFactory extends BaseColumnFactory
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