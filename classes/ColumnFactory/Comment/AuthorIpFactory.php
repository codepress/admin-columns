<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;

class AuthorIpFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Author IP', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-author_ip';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(
                         new Formatter\Comment\Property('comment_author_IP')
                     );
    }

}