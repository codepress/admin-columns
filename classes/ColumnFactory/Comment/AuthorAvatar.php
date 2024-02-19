<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;

class AuthorAvatar extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Avatar', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-author_avatar';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(
                         new Formatter\Comment\Property('comment_author_email')
                     )->add(new Formatter\Gravatar());
    }

}