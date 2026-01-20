<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Collection\LocalizeSeparator;
use AC\Formatter\MenuLink;
use AC\Formatter\Term\TermProperty;
use AC\Formatter\UsedByMenu;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\LinkToMenu;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;
use AC\Type\PostTypeSlug;

class MenuFactory extends BaseColumnFactory
{

    private PostTypeSlug $post_type;

    private LinkToMenu $link_to_menu_factory;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        LinkToMenu $link_to_menu_factory,
        PostTypeSlug $post_type
    ) {
        parent::__construct($default_settings_builder);

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
            new MenuLink(),
        ]);
        $formatters->merge(parent::get_formatters($config));
        $formatters->add(new LocalizeSeparator());

        return $formatters;
    }

}