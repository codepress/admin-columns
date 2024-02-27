<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\PostTitle;
use AC\Settings\Column\CharacterLimitFactory;
use AC\Settings\Column\PostLinkFactory;

class TitleRawFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        CharacterLimitFactory $character_limit_factory,
        PostLinkFactory $post_link_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($character_limit_factory);
        $this->add_component_factory($post_link_factory);
    }

    public function get_type(): string
    {
        return 'column-title_raw';
    }

    protected function get_label(): string
    {
        return __('Title Only', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new PostTitle());
    }
}