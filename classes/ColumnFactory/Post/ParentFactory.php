<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\PostParent;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\PostFactory;
use AC\Settings\Column\PostLinkFactory;
use AC\Settings\Column\WidthFactory;

class ParentFactory extends ColumnFactory
{

    protected $post_factory;

    protected $post_link_factory;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        PostFactory $post_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);

        $this->post_factory = $post_factory;
        $this->post_link_factory = new PostLinkFactory(null);
    }

    public function get_type(): string
    {
        return 'column-parent';
    }

    protected function get_label(): string
    {
        return __('Parent', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new PostParent());
    }

}