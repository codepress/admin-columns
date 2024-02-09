<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\Author;
use AC\Settings\Column\BeforeAfterFactory;
use AC\Settings\Column\UserFactory;

class AuthorFactory implements ColumnFactory
{

    private $builder;

    private $user_factory;

    private $before_after_factory;

    public function __construct(
        ComponentCollectionBuilder $builder,
        UserFactory $user_factory,
        BeforeAfterFactory $before_after_factory
    ) {
        $this->builder = $builder;
        $this->user_factory = $user_factory;
        $this->before_after_factory = $before_after_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->add_defaults()
                                  ->add($this->user_factory)
                                  ->add($this->before_after_factory)
                                  ->build($config);

        return new Column(
            'column-author_name',
            __('Author', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new Author()),
            $settings
        );
    }

}
