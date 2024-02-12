<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\LastModifiedAuthor;
use AC\Settings\Column\UserFactory;

class LastModifiedAuthorFactory implements ColumnFactory
{

    private $builder;

    private $user_factory;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        UserFactory $user_factory
    ) {
        $this->builder = $builder;
        $this->user_factory = $user_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()->add_defaults()
                                  ->add($this->user_factory)
                                  ->build($config);

        return new Column(
            'column-last_modified_author',
            __('Last Modified Author', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new LastModifiedAuthor()),
            $settings
        );
    }

}