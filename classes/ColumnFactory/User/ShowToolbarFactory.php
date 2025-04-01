<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class ShowToolbarFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Show Toolbar', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_show_toolbar';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->add(new Formatter\User\ShowToolbar())
                     ->add(new Formatter\YesNoIcon());
    }

}