<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Post\PostFormat;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\PostFormatIconFactory;
use AC\Settings\Column\WidthFactory;

class FormatsFactory extends ColumnFactory
{

    protected $post_format_icon_factory;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        PostFormatIconFactory $post_format_icon_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);

        $this->post_format_icon_factory = $post_format_icon_factory;
    }

    public function get_type(): string
    {
        return 'column-post_formats';
    }

    protected function get_label(): string
    {
        return __('Post Format', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new PostFormat());
    }

}