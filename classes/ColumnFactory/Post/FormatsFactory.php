<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\UseIcon;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class FormatsFactory extends BaseColumnFactory
{

    private $post_format_icon_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        UseIcon $post_format_icon_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->post_format_icon_factory = $post_format_icon_factory;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->post_format_icon_factory);
    }

    public function get_column_type(): string
    {
        return 'column-post_formats';
    }

    protected function get_label(): string
    {
        return __('Post Format', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Post\PostFormat());

        if ($config->get('use_icon') === 'on') {
            $formatters->add(new Formatter\Post\PostFormatIcon());
        }

        return parent::get_formatters($components, $config, $formatters);
    }

}