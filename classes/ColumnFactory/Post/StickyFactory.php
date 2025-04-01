<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\IsSticky;
use AC\Value\Formatter\YesNoIcon;

class StickyFactory extends ColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-sticky';
    }

    public function get_label(): string
    {
        return __('Sticky', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new IsSticky());
        $formatters->add(new YesNoIcon());

        return $formatters;
    }

}