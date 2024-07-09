<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;

class IdFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('ID', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-comment_id';
    }

}