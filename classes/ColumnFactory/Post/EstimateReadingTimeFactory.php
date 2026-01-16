<?php

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\WordsPerMinute;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class EstimateReadingTimeFactory extends BaseColumnFactory
{

    private WordsPerMinute $words_per_minute_factory;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        WordsPerMinute $words_per_minute_factory
    ) {
        parent::__construct($default_settings_builder);

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

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->words_per_minute_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new AC\Formatter\Post\PostContent());

        return $formatters;
    }

}