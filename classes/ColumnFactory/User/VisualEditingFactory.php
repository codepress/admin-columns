<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
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
        $formatters = parent::get_formatters($config);

        $formatters->add(new Formatter\User\HasRichEditing());
        $formatters->add(new Formatter\YesNoIcon());

        return $formatters;
    }

}