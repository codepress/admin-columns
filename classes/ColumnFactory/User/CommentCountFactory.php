<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;

class CommentCountFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Comments', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_commentcount';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->add(new Formatter\User\CommentCount());
    }

}