<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\BeforeMoreContent;
use AC\Settings\Column\WordLimitFactory;

class BeforeMoreFactory implements ColumnFactory
{

    private $builder;

    private $word_limit_factory;

    public function __construct(
        ComponentCollectionBuilder $builder,
        WordLimitFactory $word_limit_factory
    ) {
        $this->builder = $builder;
        $this->word_limit_factory = $word_limit_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->add_defaults()
                                  ->add($this->word_limit_factory)
                                  ->build($config);

        return new Column(
            'column-before_moretag',
            __('More Tag', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new BeforeMoreContent()),
            $settings
        );
    }

}
