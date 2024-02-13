<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;
use AC\Settings\Column\WordsPerMinuteFactory;

class EstimateReadingTimeFactory extends ColumnFactory
{

    protected $words_per_minute_factory;

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        WordsPerMinuteFactory $words_per_minute_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $name_factory, $label_factory, $width_factory);

        $this->words_per_minute_factory = $words_per_minute_factory;
    }

    public function get_type(): string
    {
        return 'column-estimated_reading_time';
    }

    protected function get_label(): string
    {
        return __('Read Time', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new Formatter\Post\PostContent());
    }

}