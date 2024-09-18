<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class DepthFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-depth';
    }

    public function get_label(): string
    {
        return __('Depth', 'codepress-admin-columns');
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Formatter\Post\Depth());
    }

}