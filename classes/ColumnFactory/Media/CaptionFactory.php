<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\Excerpt;
use AC\Setting\FormatterCollection;

class CaptionFactory extends ColumnFactory
{

    public function get_type(): string
    {
        return 'column-caption';
    }

    protected function get_label(): string
    {
        return __('Caption', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Excerpt());

        return parent::get_formatters($components, $config, $formatters);
    }

}