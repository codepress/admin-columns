<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Media\Dimensions;
use AC\Setting\FormatterCollection;

class DimensionsFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-dimensions';
    }

    protected function get_label(): string
    {
        return __('Dimensions', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Dimensions());

        return parent::get_formatters($components, $config, $formatters);
    }

}