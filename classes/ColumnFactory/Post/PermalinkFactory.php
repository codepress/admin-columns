<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Linkable;
use AC\Value\Formatter\Post\Permalink;

class PermalinkFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-permalink';
    }

    public function get_label(): string
    {
        return __('Permalink', 'codepress-admin-columns');
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Permalink());
        $formatters->add(new Linkable());
    }

}