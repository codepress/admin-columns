<?php

declare(strict_types=1);

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

class ShowToolbarFactory extends BaseColumnFactory
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
                     ->add(new AC\Formatter\User\ShowToolbar())
                     ->add(new AC\Formatter\YesNoIcon());
    }

}