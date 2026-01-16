<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Post\IsSticky;
use AC\Formatter\YesNoIcon;
use AC\FormatterCollection;
use AC\Setting\Config;

class StickyFactory extends BaseColumnFactory
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