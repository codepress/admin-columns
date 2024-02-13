<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\UsedByMenu;
use AC\Settings\Column\LinkToMenuFactory;

class MenuFactory implements ColumnFactory
{

    private $builder;

    private $link_to_menu_factory;

    private $post_type;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        LinkToMenuFactory $link_to_menu_factory,
        string $post_type
    ) {
        $this->builder = $builder;
        $this->link_to_menu_factory = $link_to_menu_factory;
        $this->post_type = $post_type;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()->add_defaults()
                                  ->add($this->link_to_menu_factory)
                                  ->build($config);

        return new Column(
            'column-used_by_menu',
            __('Menu', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new UsedByMenu($this->post_type)),
            $settings
        );
    }

}