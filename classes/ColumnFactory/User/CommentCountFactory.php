<?php

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class CommentCountFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Comments', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_commentcount';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AC\Value\Formatter\User\CommentCount());

        return $formatters;
    }

}