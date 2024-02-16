<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\PostStatusIconFactory;

class StatusFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        PostStatusIconFactory $post_status_icon_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($post_status_icon_factory);
    }

    protected function get_label(): string
    {
        return __('Status', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-status';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->prepend(new Formatter\Post\PostStatus());
    }

}