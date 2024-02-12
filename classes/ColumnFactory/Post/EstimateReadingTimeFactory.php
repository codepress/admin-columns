<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Settings\Column\WordsPerMinuteFactory;

class EstimateReadingTimeFactory implements ColumnFactory
{

    private $builder;

    private $words_per_minute_factory;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        WordsPerMinuteFactory $words_per_minute_factory
    ) {
        $this->builder = $builder;
        $this->words_per_minute_factory = $words_per_minute_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()
                                  ->add_defaults()
                                  ->add($this->words_per_minute_factory)
                                  ->build($config);

        return new Column(
            'column-estimated_reading_time',
            __('Read Time', 'codepress-admin-columns'),
            Formatter\Aggregate::from_settings($settings)->prepend(new Formatter\Post\PostContent()),
            $settings
        );
    }

}