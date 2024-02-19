<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Post\Excerpt;

class CaptionFactory extends ColumnFactory
{

    // Group to group: 'custom'

    public function get_type(): string
    {
        return 'column-caption';
    }

    protected function get_label(): string
    {
        return __('Caption', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new Excerpt());
    }

}