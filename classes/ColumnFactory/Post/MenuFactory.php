<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\UsedByMenu;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\LinkToMenuFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;

class MenuFactory extends ColumnFactory
{

    protected $link_to_menu_factory;

    private $post_type;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        LinkToMenuFactory $link_to_menu_factory,
        string $post_type
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);

        $this->link_to_menu_factory = $link_to_menu_factory;
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

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new UsedByMenu($this->post_type));
    }

}