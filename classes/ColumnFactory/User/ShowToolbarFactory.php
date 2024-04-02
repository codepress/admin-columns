<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class ShowToolbarFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('Show Toolbar', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_show_toolbar';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\User\ShowToolbar());
        $formatters->add(new Formatter\YesNoIcon());

        return parent::get_formatters($components, $config, $formatters);
    }

}