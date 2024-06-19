<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Linkable;
use AC\Setting\Formatter\Post\Permalink;
use AC\Setting\FormatterCollection;

class PermalinkFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-permalink';
    }

    protected function get_label(): string
    {
        return __('Permalink', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Permalink());
        $formatters->add(new Linkable());

        return parent::get_formatters($components, $config, $formatters);
    }

}