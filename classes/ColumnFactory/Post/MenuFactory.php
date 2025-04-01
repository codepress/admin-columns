<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\LinkToMenu;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Type\PostTypeSlug;
use AC\Value\Formatter\Collection\LocalizeSeparator;
use AC\Value\Formatter\Term\TermProperty;
use AC\Value\Formatter\UsedByMenu;

class MenuFactory extends ColumnFactory
{

    private PostTypeSlug $post_type;

    private LinkToMenu $link_to_menu_factory;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        LinkToMenu $link_to_menu_factory,
        PostTypeSlug $post_type
    ) {
        parent::__construct($base_settings_builder);

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

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->link_to_menu_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = new FormatterCollection([
            new UsedByMenu($this->post_type),
            new TermProperty('name'),
        ]);
        $formatters->merge(parent::get_formatters($config));
        $formatters->add(new LocalizeSeparator());

        return $formatters;
    }

}