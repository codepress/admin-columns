<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class AuthorNameFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('Author Name', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-author_name';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Comment\Property('comment_author'));

        return parent::get_formatters($components, $config, $formatters);
    }
}