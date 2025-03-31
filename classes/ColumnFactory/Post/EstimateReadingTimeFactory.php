<?php

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\WordsPerMinute;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class EstimateReadingTimeFactory extends BaseColumnFactory
{

    private WordsPerMinute $words_per_minute_factory;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        WordsPerMinute $words_per_minute_factory
    ) {
        parent::__construct($base_settings_builder);

        $this->words_per_minute_factory = $words_per_minute_factory;
    }

    public function get_column_type(): string
    {
        return 'column-estimated_reading_time';
    }

    public function get_label(): string
    {
        return __('Read Time', 'codepress-admin-columns');
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->words_per_minute_factory);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new AC\Value\Formatter\Post\PostContent());
    }

}