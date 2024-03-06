<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\AggregateBuilderFactory;

class IdFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        BeforeAfter $before_after_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($before_after_factory);
    }

    public function get_type(): string
    {
        return 'column-postid';
    }

    protected function get_label(): string
    {
        return __('ID', 'codepress-admin-columns');
    }

}