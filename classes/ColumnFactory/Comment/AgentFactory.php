<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Comment\Property;

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
            new Property('comment_agent')
        );
    }

}