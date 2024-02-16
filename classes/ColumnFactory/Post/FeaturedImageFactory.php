<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\FeaturedImage;
use AC\Settings\Column\ImageFactory;

class FeaturedImageFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        ImageFactory $image_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($image_factory);
    }

    public function get_type(): string
    {
        return 'column-featured_image';
    }

    protected function get_label(): string
    {
        return __('Featured Image', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->prepend(new FeaturedImage());
    }

}