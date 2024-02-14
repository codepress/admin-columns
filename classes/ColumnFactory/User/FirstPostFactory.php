<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\PostFactory;
use AC\Settings\Column\PostStatusFactory;
use AC\Settings\Column\PostTypeFactory;

class FirstPostFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        PostTypeFactory $post_type_factory,
        PostStatusFactory $post_status_factory,
        PostFactory $post_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($post_type_factory);
        $this->add_component_factory($post_status_factory);
        $this->add_component_factory($post_factory);
    }

    protected function get_label(): string
    {
        return __('First Post', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-first_post';
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new Formatter\User\Meta('first_name'));
    }

}