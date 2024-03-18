<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\UseIcon;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;

class FormatsFactory extends ColumnFactory
{

    private $post_format_icon_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        UseIcon $post_format_icon_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->post_format_icon_factory = $post_format_icon_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->post_format_icon_factory);
    }

    public function get_type(): string
    {
        return 'column-post_formats';
    }

    protected function get_label(): string
    {
        return __('Post Format', 'codepress-admin-columns');
    }

    protected function get_formatters(ComponentCollection $components, Config $config): array
    {
        $formatters = parent::get_formatters($components, $config);

        if ($config->get('use_icon')) {
            return array_merge([new Formatter\Post\PostFormatIcon()], $formatters);
        }

        return $formatters;
    }

}