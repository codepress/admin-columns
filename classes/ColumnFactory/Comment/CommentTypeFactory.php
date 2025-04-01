<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\Property;

class CommentTypeFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Type', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-type';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new Property('comment_type'));

        return $formatters;
    }
}