<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\PostContent;
use AC\Settings\Column\BeforeAfterFactory;
use AC\Settings\Column\StringLimitFactory;

class DescriptionFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        StringLimitFactory $string_limit_factory,
        BeforeAfterFactory $before_after_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($string_limit_factory);
        $this->add_component_factory($before_after_factory);
    }

    // Group to group: 'custom'

    public function get_type(): string
    {
        return 'column-description';
    }

    protected function get_label(): string
    {
        return __('Description', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->prepend(new PostContent());
    }

}