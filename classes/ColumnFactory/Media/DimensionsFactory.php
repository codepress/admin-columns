<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\Dimensions;

class DimensionsFactory extends BaseColumnFactory
{

    protected function get_group(): ?string
    {
        return 'media-image';
    }

    public function get_column_type(): string
    {
        return 'column-dimensions';
    }

    public function get_label(): string
    {
        return __('Dimensions', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Dimensions());

        return $formatters;
    }

}