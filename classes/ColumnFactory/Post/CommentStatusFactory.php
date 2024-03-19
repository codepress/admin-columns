<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\HasCommentStatus;

class CommentStatusFactory extends ColumnFactory
{

    public function get_type(): string
    {
        return 'column-comment_status';
    }

    protected function get_label(): string
    {
        return __('Allow Comments', 'codepress-admin-columns');
    }

    protected function get_formatters(ComponentCollection $components, Config $config): array
    {
        return array_merge(
            [
                new HasCommentStatus('open'),
            ],
            parent::get_formatters($components, $config)
        );
    }

}