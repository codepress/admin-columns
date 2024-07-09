<?php

declare(strict_types=1);

namespace AC\ColumnFactory\CustomField;

use AC\Column\BaseColumnFactory;
use AC\Type\ColumnParent;

class TextFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-meta-text';
    }

    public function get_label(): string
    {
        return __('Text', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'custom_field';
    }

    protected function get_parent(): ColumnParent
    {
        return new ColumnParent(
            __('Custom Field', 'codepress-admin-columns'),
            __('Basic', 'codepress-admin-columns')
        );
    }

}