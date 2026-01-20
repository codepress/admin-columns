<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Comment\StatusLabel;
use AC\FormatterCollection;
use AC\Setting\Config;

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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new StatusLabel());

        return $formatters;
    }
}