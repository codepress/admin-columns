<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\PostStatusIcon;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

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

    public function get_label(): string
    {
        return __('Status', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-status';
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        parent::add_component_factories($factories);

        $factories->add($this->post_status_icon);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new Formatter\Post\PostStatus());
    }

}