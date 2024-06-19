<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Comment\ParentId;
use AC\Setting\Formatter\Comment\ReplyToLink;
use AC\Setting\FormatterCollection;

class ReplyToFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('In Reply To', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-reply_to';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new ParentId());
        $formatters->add(new ReplyToLink());

        return parent::get_formatters($components, $config, $formatters);
    }
}