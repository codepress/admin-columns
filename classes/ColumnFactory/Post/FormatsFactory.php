<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactory\UseIcon;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;

class FormatsFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        UseIcon $post_format_icon_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($post_format_icon_factory);
    }

    public function get_type(): string
    {
        return 'column-post_formats';
    }

    protected function get_label(): string
    {
        return __('Post Format', 'codepress-admin-columns');
    }

    protected function create_formatter(Config $config): Formatter
    {
        if ($config->get('use_icon')) {
            return new Formatter\Post\PostFormatIcon();
        }

        return new Formatter\NullFormatter();
    }

}