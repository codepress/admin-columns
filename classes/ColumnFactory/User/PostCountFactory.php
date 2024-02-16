<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\PostStatusFactory;
use AC\Settings\Column\PostTypeFactory;

class PostCountFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        PostTypeFactory $post_type_factory,
        PostStatusFactory $post_status_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($post_type_factory);
        $this->add_component_factory($post_status_factory);
    }

    protected function get_label(): string
    {
        return __('Post Count', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_postcount';
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        // TODO apply specific post count formatter based on PostCountColumn
        return parent::create_formatter_builder($components);
    }

}