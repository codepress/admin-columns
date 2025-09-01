<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\Property;
use AC\Value\Formatter\Linkable;

class AuthorUrlFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Author URL', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-author_url';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new Property('comment_author_url'));
        $formatters->add(new Linkable(null, '_self', true));

        return $formatters;
    }

}