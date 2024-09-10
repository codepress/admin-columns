<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\IsSticky;
use AC\Value\Formatter\YesNoIcon;

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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new IsSticky());
        $formatters->add(new YesNoIcon());
    }

}