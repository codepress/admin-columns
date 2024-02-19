<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;

class AuthorNameFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Author Name', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-author_name';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(
                         new Formatter\Comment\Property('comment_author')
                     );
    }

}