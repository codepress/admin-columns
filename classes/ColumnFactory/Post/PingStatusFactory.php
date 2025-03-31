<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\PingStatus;
use AC\Value\Formatter\YesNoIcon;

class PingStatusFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Ping Status', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-ping_status';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new PingStatus());
        $formatters->add(new YesNoIcon());

        return $formatters;
    }

}