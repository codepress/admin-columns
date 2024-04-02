<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\PostStatusIcon;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class StatusFactory extends BaseColumnFactory
{

    private $post_status_icon;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        PostStatusIcon $post_status_icon
    ) {
        parent::__construct($component_factory_registry);

        $this->post_status_icon = $post_status_icon;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->post_status_icon);
    }

    protected function get_label(): string
    {
        return __('Status', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-status';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Post\PostStatus());

        return parent::get_formatters($components, $config, $formatters);
    }

}