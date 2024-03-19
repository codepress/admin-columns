<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\ImageSize;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\FeaturedImage;
use AC\Setting\FormatterCollection;

class FeaturedImageFactory extends ColumnFactory
{

    private $image_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ImageSize $image_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->image_factory = $image_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();
        
        $this->add_component_factory($this->image_factory);
    }

    public function get_type(): string
    {
        return 'column-featured_image';
    }

    protected function get_label(): string
    {
        return __('Featured Image', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new FeaturedImage());

        return parent::get_formatters($components, $config, $formatters);
    }

}