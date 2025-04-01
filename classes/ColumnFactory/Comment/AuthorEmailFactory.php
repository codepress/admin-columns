<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class AuthorEmailFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Author Email', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-author_email';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return new FormatterCollection([
            new Formatter\Comment\Property('comment_author_email'),
            new Formatter\Linkable(),
        ]);
    }

}