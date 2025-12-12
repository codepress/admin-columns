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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = new FormatterCollection([
            new Formatter\Comment\CommentObject(),
            new Formatter\Avatar(),
        ]);

        return $formatters->merge(
            parent::get_formatters($config)
        );
    }

}