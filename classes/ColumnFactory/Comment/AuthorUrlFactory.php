<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Comment\Property;
use AC\Formatter\Linkable;
use AC\FormatterCollection;
use AC\Setting\Config;

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