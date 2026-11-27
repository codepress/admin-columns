<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

class DepthFactory extends BaseColumnFactory
{
    public function get_column_type(): string
    {
        return 'column-depth';
    }

    public function get_label(): string
    {
        return __('Depth', 'codepress-admin-columns');
    }

    public function get_description(): ?string
    {
        return __('Level of this item in the page hierarchy, where top-level items are 1.', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AC\Formatter\Post\Depth());

        return $formatters;
    }

}
