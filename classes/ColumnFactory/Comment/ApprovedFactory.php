<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class ApprovedFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Approved', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-approved';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = new FormatterCollection([
            new Formatter\Comment\Property('comment_approved'),
            new Formatter\YesNoIcon(),
        ]);

        return $formatters->merge(parent::get_formatters($config));
    }

}