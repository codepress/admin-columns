<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\Post\FeaturedImageDisplay;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class FeaturedImageFactory extends BaseColumnFactory
{

    private $featured_image_component;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        FeaturedImageDisplay $featured_image_component
    ) {
        parent::__construct($component_factory_registry);

        $this->featured_image_component = $featured_image_component;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->featured_image_component);
    }

    public function get_column_type(): string
    {
        return 'column-featured_image';
    }

    public function get_label(): string
    {
        return __('Featured Image', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Post\FeaturedImage());

        return parent::get_formatters($components, $config, $formatters);
    }

}