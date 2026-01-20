<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Comment\Property;
use AC\FormatterCollection;
use AC\Setting\Config;

class CommentTypeFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Type', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-type';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new Property('comment_type'));

        return $formatters;
    }
}