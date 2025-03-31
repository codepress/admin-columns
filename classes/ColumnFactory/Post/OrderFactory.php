<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\Order;

class OrderFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-order';
    }

    public function get_label(): string
    {
        return __('Order', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Order());

        return $formatters;
    }

}