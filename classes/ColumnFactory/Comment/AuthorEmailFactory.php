<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class AuthorEmailFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Author Email', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-author_email';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new Formatter\Linkable());
        $formatters->prepend(new Formatter\Comment\Property('comment_author_email'));
    }

}