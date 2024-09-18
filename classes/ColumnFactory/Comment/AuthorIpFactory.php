<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class AuthorIpFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Author IP', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-author_ip';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new Formatter\Comment\Property('comment_author_IP'));
    }

}