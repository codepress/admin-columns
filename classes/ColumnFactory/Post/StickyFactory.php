<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;

class StickyFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);
    }

    public function get_type(): string
    {
        return 'column-sticky';
    }

    protected function get_label(): string
    {
        return __('Sticky', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new Formatter\Post\IsSticky());
    }

}