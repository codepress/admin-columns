<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\PingStatus;
use AC\Setting\FormatterCollection;

class PingStatusFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('Ping Status', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-ping_status';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new PingStatus());

        return parent::get_formatters($components, $config, $formatters);
    }

}