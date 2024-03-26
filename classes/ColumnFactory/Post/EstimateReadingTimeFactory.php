<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\WordsPerMinute;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class EstimateReadingTimeFactory extends BaseColumnFactory
{

    private $words_per_minute_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        WordsPerMinute $words_per_minute_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->words_per_minute_factory = $words_per_minute_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->words_per_minute_factory);
    }

    public function get_type(): string
    {
        return 'column-estimated_reading_time';
    }

    protected function get_label(): string
    {
        return __('Read Time', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Post\PostContent());

        return parent::get_formatters($components, $config, $formatters);
    }

}