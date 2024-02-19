<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Comment\Property;

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

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->add(new Property('comment_type'));
    }

}