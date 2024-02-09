<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Settings\Column\CommentsFactory;
use AC\Settings\Column\StringLimitFactory;

class CommentFactory implements ColumnFactory
{

    private $builder;

    private $string_limit_factory;

    public function __construct(ComponentCollectionBuilder $builder, StringLimitFactory $string_limit_factory)
    {
        $this->builder = $builder;
        $this->string_limit_factory = $string_limit_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->add_defaults()
                                  ->add(new CommentsFactory())
                                  ->add($this->string_limit_factory)
                                  ->build($config);

        return new Column(
            'column-comment_count',
            __('Comment Count', 'codepress-admin-columns'),
            Aggregate::from_settings($settings),
            $settings
        );
    }

}