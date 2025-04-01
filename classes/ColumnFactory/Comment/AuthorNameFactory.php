<?php

namespace AC\ColumnFactory\Comment;

use AC;
use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class AuthorNameFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Author Name', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-author_name';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->prepend(new AC\Value\Formatter\Comment\Property('comment_author'));

        return $formatters;
    }

}