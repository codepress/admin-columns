<?php

declare(strict_types=1);

namespace AC\ColumnFactory\CustomField;

use AC\Column\BaseColumnFactory;
use AC\Type\ColumnParent;

class NumberFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-meta-number';
    }

    protected function get_label(): string
    {
        return __('Number', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'custom_field';
    }

    protected function get_parent(): ?ColumnParent
    {
        return new ColumnParent(
            __('Custom Field', 'codepress-admin-columns'),
            __('Basic', 'codepress-admin-columns')
        );
    }

}