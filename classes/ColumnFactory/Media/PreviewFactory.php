<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Media\AttachmentUrl;

class PreviewFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);
    }

    public function get_type(): string
    {
        return 'column-preview';
    }

    protected function get_label(): string
    {
        return __('Preview', 'codepress-admin-columns');
    }

    // TODO implement and check Ajax Modal Values
    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new AttachmentUrl());
    }

}