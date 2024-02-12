<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\DatePublishFormatted;
use AC\Setting\Formatter\Post\PostDate;
use AC\Settings\Column\DateFactory;

class DatePublishFactory implements ColumnFactory
{

    private $builder;

    private $date_factory;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        DateFactory $date_factory
    ) {
        $this->builder = $builder;
        $this->date_factory = $date_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()
                                  ->add_defaults()
                                  ->add($this->date_factory)
                                  ->build($config);

        $formatter = Aggregate::from_settings($settings)
                              ->prepend(new PostDate())
                              ->add(new DatePublishFormatted());

        return new Column(
            'column-date_published',
            __('Date Published', 'codepress-admin-columns'),
            $formatter,
            $settings
        );
    }

}