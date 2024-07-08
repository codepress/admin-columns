<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\LinkToMenu;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Type\PostTypeSlug;
use AC\Value\Formatter\Collection\LocalizeSeparator;
use AC\Value\Formatter\Post\UsedByMenu;
use AC\Value\Formatter\Term\TermProperty;

class MenuFactory extends BaseColumnFactory
{

    private $post_type;

    private $link_to_menu_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        LinkToMenu $link_to_menu_factory,
        PostTypeSlug $post_type
    ) {
        parent::__construct($component_factory_registry);

        $this->post_type = $post_type;
        $this->link_to_menu_factory = $link_to_menu_factory;
    }

    public function get_post_type(): PostTypeSlug
    {
        return $this->post_type;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->link_to_menu_factory);
    }

    public function get_column_type(): string
    {
        return 'column-used_by_menu';
    }

    protected function get_label(): string
    {
        return __('Menu', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new UsedByMenu($this->post_type));
        $formatters->add(new TermProperty('name'));
        $formatters = parent::get_formatters($components, $config, $formatters);
        $formatters->add(new LocalizeSeparator());

        return $formatters;
    }

}