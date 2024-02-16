<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Linkable;
use AC\Setting\Formatter\Post\Permalink;

class PermalinkFactory extends ColumnFactory
{

    public function get_type(): string
    {
        return 'column-permalink';
    }

    protected function get_label(): string
    {
        return __('Permalink', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): Formatter\AggregateBuilder {
        return parent::create_formatter_builder($components, $config)
                     ->add(new Permalink())
                     ->add(new Linkable());
    }

}