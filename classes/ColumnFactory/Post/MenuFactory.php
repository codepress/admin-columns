<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\LinkToMenu;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Type\PostTypeSlug;
use AC\Value\Formatter\Collection\LocalizeSeparator;
use AC\Value\Formatter\Term\TermProperty;
use AC\Value\Formatter\UsedByMenu;

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

    public function get_column_type(): string
    {
        return 'column-used_by_menu';
    }

    public function get_label(): string
    {
        return __('Menu', 'codepress-admin-columns');
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->link_to_menu_factory);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        // TODO test
        $formatters->prepend(new TermProperty('name'));
        $formatters->prepend(new UsedByMenu($this->post_type));
        $formatters->add(new LocalizeSeparator());
    }

}