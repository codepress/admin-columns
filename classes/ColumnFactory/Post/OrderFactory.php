<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\Order;

class OrderFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-order';
    }

    protected function get_label(): string
    {
        return __('Order', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Order());

        return parent::get_formatters($components, $config, $formatters);
    }

}