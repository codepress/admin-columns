<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\HasCommentStatus;
use AC\Setting\FormatterCollection;

class CommentStatusFactory extends BaseColumnFactory
{

    public function get_type(): string
    {
        return 'column-comment_status';
    }

    protected function get_label(): string
    {
        return __('Allow Comments', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new HasCommentStatus('open'));

        return parent::get_formatters($components, $config, $formatters);
    }

}