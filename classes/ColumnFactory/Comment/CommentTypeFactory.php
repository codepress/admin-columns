<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Comment\Property;
use AC\Setting\FormatterCollection;

class CommentTypeFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Type', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-type';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Property('comment_type'));

        return parent::get_formatters($components, $config, $formatters);
    }
}