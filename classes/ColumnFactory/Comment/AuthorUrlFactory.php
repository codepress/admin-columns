<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Comment\Property;
use AC\Setting\Formatter\Linkable;
use AC\Setting\FormatterCollection;

class AuthorUrlFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Author URL', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-author_url';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Property('comment_author_url'));
        $formatters->add(new Linkable());

        return parent::get_formatters($components, $config, $formatters);
    }
}