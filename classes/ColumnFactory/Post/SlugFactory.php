<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\Slug;
use AC\Settings\Column\CharacterLimitFactory;

class SlugFactory implements ColumnFactory
{

    private $builder;

    private $character_limit_factory;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        CharacterLimitFactory $character_limit_factory
    ) {
        $this->builder = $builder;
        $this->character_limit_factory = $character_limit_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()
                                  ->add_defaults()
                                  ->add($this->character_limit_factory)
                                  ->build($config);

        return new Column(
            'column-slug',
            __('Slug', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new Slug()),
            $settings
        );
    }

}