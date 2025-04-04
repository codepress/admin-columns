<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\LinkableCommentDate;
use AC\Value\Formatter\Comment\Property;

class DateGmtFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Date GMT', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-date_gmt';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new Property('comment_date_gmt'));
        $formatters->add(new LinkableCommentDate());

        return $formatters;
    }

}