<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;

class IdFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('ID', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-comment_id';
    }

}