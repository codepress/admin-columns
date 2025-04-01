<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class AuthorIpFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Author IP', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-author_ip';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->prepend(new Formatter\Comment\Property('comment_author_IP'));

        return $formatters;
    }

}