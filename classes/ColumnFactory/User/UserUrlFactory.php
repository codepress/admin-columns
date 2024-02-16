<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;

class UserUrlFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Website', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-user_url';
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new Formatter\User\Property('user_url'))
                     ->add(new Formatter\Linkable('_blank'));
    }

}