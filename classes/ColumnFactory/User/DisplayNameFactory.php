<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;

class DisplayNameFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Display Name', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-display_name';
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->prepend(new Formatter\User\Property('display_name'));
    }

}