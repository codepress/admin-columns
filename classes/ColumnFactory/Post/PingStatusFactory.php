<?php

declare(strict_types=1);

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
        return $this->aggregate_formatter_builder_factory
            ->create()
            ->add(new PingStatus());
    }

}