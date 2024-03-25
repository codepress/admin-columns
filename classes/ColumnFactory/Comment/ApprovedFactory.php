<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Post\Path;
use AC\Setting\FormatterCollection;

class ApprovedFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Approved', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-approved';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Comment\Property('comment_approved'));
        $formatters->add(new Formatter\YesNoIcon());

        return parent::get_formatters($components, $config, $formatters);
    }
}