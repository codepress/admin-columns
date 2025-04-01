<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\HasCommentStatus;

class CommentStatusFactory extends ColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-comment_status';
    }

    public function get_label(): string
    {
        return __('Allow Comments', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new HasCommentStatus('open'));

        return $formatters;
    }

}