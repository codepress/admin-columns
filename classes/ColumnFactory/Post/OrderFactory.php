<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Post\Order;

class OrderFactory extends ColumnFactory
{

    public function get_type(): string
    {
        return 'column-order';
    }

    protected function get_label(): string
    {
        return __('Order', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->add(new Order());
    }

}