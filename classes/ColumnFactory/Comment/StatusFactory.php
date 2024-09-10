<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\StatusLabel;

class StatusFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Status', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-status';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new StatusLabel());
    }
}