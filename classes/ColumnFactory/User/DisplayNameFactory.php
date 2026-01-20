<?php

declare(strict_types=1);

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

class DisplayNameFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Display Name', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-display_name';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AC\Formatter\User\Property('display_name'));

        return $formatters;
    }

}