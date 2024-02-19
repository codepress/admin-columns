<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\User\Property;
use AC\Settings;

class PostFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        Settings\Column\PostFactory $post_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($post_factory);
    }

    protected function get_label(): string
    {
        return __('Post', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-post';
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new Property('comment_post_ID'));
    }

}