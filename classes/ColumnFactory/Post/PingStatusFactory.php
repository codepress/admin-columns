<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Post\PingStatus;

class PingStatusFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Ping Status', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-ping_status';
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->add(new PingStatus());
    }

}