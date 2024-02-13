<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;

class DepthFactory extends ColumnFactory
{

    public function get_type(): string
    {
        return 'column-depth';
    }

    protected function get_label(): string
    {
        return __('Depth', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->add(new Formatter\Post\Depth());
    }

}