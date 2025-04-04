<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Id;

class IdFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('ID', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-comment_id';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new Id());

        return $formatters;
    }

}