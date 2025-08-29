<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\Slug;

class SlugFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Slug', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-slug';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new Slug());

        return $formatters;
    }

}