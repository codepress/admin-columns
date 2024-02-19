<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Comment\StatusLabel;

class StatusFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Status', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-status';
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->add(new StatusLabel());
    }

}