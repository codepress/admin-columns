<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;
use AC\Value\Formatter;

class VisualEditingFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Visual Editor', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-rich_editing';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->add(new \AC\Formatter\User\HasRichEditing())
                     ->add(new \AC\Formatter\YesNoIcon());
    }

}