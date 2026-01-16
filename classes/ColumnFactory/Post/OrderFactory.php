<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Post\Order;
use AC\FormatterCollection;
use AC\Setting\Config;

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