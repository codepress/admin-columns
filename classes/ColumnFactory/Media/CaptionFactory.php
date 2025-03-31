<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\Excerpt;

class CaptionFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-caption';
    }

    public function get_label(): string
    {
        return __('Caption', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Excerpt());

        return $formatters;
    }

}