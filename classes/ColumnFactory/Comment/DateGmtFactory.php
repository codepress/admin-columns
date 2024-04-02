<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Comment\LinkableCommentDate;
use AC\Setting\Formatter\Comment\Property;
use AC\Setting\FormatterCollection;

class DateGmtFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('Date GMT', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-date_gmt';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Property('comment_date_gmt'));
        $formatters->add(new LinkableCommentDate());

        return parent::get_formatters($components, $config, $formatters);
    }
}