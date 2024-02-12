<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\PostContent;
use AC\Settings\Column\BeforeAfterFactory;
use AC\Settings\Column\StringLimitFactory;

class ContentFactory implements ColumnFactory
{

    private $builder;

    private $string_limit_factory;

    private $before_after_factory;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        StringLimitFactory $string_limit_factory,
        BeforeAfterFactory $before_after_factory
    ) {
        $this->builder = $builder;
        $this->string_limit_factory = $string_limit_factory;
        $this->before_after_factory = $before_after_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()
                                  ->add_defaults()
                                  ->add($this->string_limit_factory)
                                  ->add($this->before_after_factory)
                                  ->build($config);

        return new Column(
            'column-content',
            __('Content', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new PostContent()),
            $settings
        );
    }

}