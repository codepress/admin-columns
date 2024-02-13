<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\CharacterLimitFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\PostLinkFactory;
use AC\Settings\Column\WidthFactory;

class TitleRawFactory extends ColumnFactory
{

    protected $character_limit_factory;

    protected $post_link_factory;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        CharacterLimitFactory $character_limit_factory,
        PostLinkFactory $post_link_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);
        $this->character_limit_factory = $character_limit_factory;
        $this->post_link_factory = $post_link_factory;
    }

    public function get_type(): string
    {
        return 'column-title_raw';
    }

    protected function get_label(): string
    {
        return __('Title Only', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new Formatter\Post\PostTitle());
    }
}