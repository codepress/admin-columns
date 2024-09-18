<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\Shortcodes;

class ShortcodesFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Shortcodes', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-shortcode';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Shortcodes());
    }

}