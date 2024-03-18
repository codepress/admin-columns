<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;

class DepthFactory extends ColumnFactory
{

    public function get_type(): string
    {
        return 'column-depth';
    }

    protected function get_label(): string
    {
        return __('Depth', 'codepress-admin-columns');
    }

    protected function get_formatters(ComponentCollection $components, $config): array
    {
        return array_merge(parent::get_formatters($components, $config), [new Formatter\Post\Depth()]);
    }

}