<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\UsedByMenu;
use AC\Settings\Column\LinkToMenuFactory;

class MenuFactory extends ColumnFactory
{

    private $post_type;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        LinkToMenuFactory $link_to_menu_factory,
        string $post_type
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($link_to_menu_factory);
        $this->post_type = $post_type;
    }

    public function get_type(): string
    {
        return 'column-used_by_menu';
    }

    protected function get_label(): string
    {
        return __('Menu', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->prepend(new UsedByMenu($this->post_type));
    }

}