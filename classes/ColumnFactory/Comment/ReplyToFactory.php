<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Comment\ParentId;
use AC\Setting\Formatter\Comment\ReplyToLink;

class ReplyToFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('In Reply To', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-reply_to';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new ParentId())
                     ->add(new ReplyToLink());
    }

}