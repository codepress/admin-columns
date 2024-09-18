<?php

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class CommentCountFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Comments', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_commentcount';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new AC\Value\Formatter\User\CommentCount());
    }

}