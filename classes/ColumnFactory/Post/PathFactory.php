<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Post\Path;
use AC\FormatterCollection;
use AC\Setting\Config;

class PathFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-path';
    }

    public function get_label(): string
    {
        return __('Path', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Path());

        return $formatters;
    }

}