<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;

class AgentFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Agent', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-agent';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->prepend(
            new Formatter\Comment\Property('comment_agent')
        );
    }

}