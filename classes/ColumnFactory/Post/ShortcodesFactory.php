<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\Shortcodes;

class ShortcodesFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Shortcodes', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-shortcode';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Shortcodes());

        return $formatters;
    }

}