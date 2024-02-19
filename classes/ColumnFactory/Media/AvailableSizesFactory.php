<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Media\AvailableSizes;
use AC\Settings\Column\MissingImageSizeFactory;

class AvailableSizesFactory extends ColumnFactory
{

    // Group to group: 'media-image'

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        MissingImageSizeFactory $missing_image_size_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($missing_image_size_factory);
    }

    public function get_type(): string
    {
        return 'column-available_sizes';
    }

    protected function get_label(): string
    {
        return __('Available Sizes', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new AvailableSizes(true));
    }

}