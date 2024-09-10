<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\Property;

class CommentTypeFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Type', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-type';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Property('comment_type'));
    }
}