<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;

class CommentCountFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);
    }

    protected function get_label(): string
    {
        return __('Comments', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_commentcount';
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->add(new Formatter\User\CommentCount());
    }

}