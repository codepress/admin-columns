<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;

class AuthorSlugFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Author Slug', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_nicename';
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)
                     ->add(new Formatter\User\Property('user_nicename'))
                     ->add(new Formatter\User\AuthorPostUrl());
    }

}