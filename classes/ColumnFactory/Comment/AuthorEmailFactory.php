<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class AuthorEmailFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('Author Email', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-author_email';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Comment\Property('comment_author_email'));
        $formatters->add(new Formatter\Linkable());

        return parent::get_formatters($components, $config, $formatters);
    }
}