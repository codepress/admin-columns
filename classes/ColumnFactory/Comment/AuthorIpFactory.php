<?php

namespace AC\ColumnFactory\Comment;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->prepend(new AC\Formatter\Comment\Property('comment_author_IP'));

        return $formatters;
    }

}