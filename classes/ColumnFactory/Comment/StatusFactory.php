<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\StatusLabel;

class StatusFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Status', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-status';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new StatusLabel());

        return $formatters;
    }
}