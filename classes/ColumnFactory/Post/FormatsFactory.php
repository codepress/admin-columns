<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\UseIcon;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class FormatsFactory extends BaseColumnFactory
{

    private UseIcon $post_format_icon_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        UseIcon $post_format_icon_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->post_format_icon_factory = $post_format_icon_factory;
    }

    public function get_column_type(): string
    {
        return 'column-post_formats';
    }

    public function get_label(): string
    {
        return __('Post Format', 'codepress-admin-columns');
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->post_format_icon_factory);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new Formatter\Post\PostFormat());

        if ('on' === $config->get('use_icon')) {
            $formatters->add(new Formatter\Post\PostFormatIcon());
        } else {
            $formatters->add(new Formatter\Post\PostFormatLabel());
        }
    }

}