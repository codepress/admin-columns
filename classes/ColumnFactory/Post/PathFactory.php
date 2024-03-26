<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\Path;
use AC\Setting\FormatterCollection;

class PathFactory extends BaseColumnFactory
{

    public function get_type(): string
    {
        return 'column-path';
    }

    protected function get_label(): string
    {
        return __('Path', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Path());

        return parent::get_formatters($components, $config, $formatters);
    }
}