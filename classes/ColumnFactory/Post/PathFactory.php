<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\Path;

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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Path());
    }

}