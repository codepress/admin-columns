<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Comment;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

class AuthorEmailFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Author Email', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-author_email';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return new FormatterCollection([
            new AC\Formatter\Comment\Property('comment_author_email'),
            new AC\Formatter\Linkable(),
        ]);
    }

}