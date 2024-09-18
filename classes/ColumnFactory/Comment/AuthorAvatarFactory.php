<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class AuthorAvatarFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Avatar', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-author_avatar';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new Formatter\Gravatar());
        $formatters->prepend(new Formatter\Comment\Property('comment_author_email'));
    }

}