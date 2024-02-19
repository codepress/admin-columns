<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Media\Dimensions;

class DimensionsFactory extends ColumnFactory
{

    // Group to group: 'custom'

    public function get_type(): string
    {
        return 'column-dimensions';
    }

    protected function get_label(): string
    {
        return __('Dimensions', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(
        ComponentCollection $components,
        Config $config
    ): AggregateBuilder {
        return parent::create_formatter_builder($components, $config)->add(new Dimensions());
    }

}