<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\ParentId;
use AC\Value\Formatter\Comment\ReplyToLink;

class ReplyToFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('In Reply To', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-reply_to';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new ParentId());
        $formatters->add(new ReplyToLink());

        return $formatters;
    }

}