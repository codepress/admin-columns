<?php

namespace AC\ColumnFactory\Comment;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class AuthorNameFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Author Name', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-author_name';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new AC\Value\Formatter\Comment\Property('comment_author'));
    }

}