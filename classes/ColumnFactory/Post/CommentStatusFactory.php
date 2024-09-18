<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\HasCommentStatus;

class CommentStatusFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-comment_status';
    }

    public function get_label(): string
    {
        return __('Allow Comments', 'codepress-admin-columns');
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new HasCommentStatus('open'));
    }

}