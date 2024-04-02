<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\Shortcodes;
use AC\Setting\FormatterCollection;

class ShortcodesFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('Shortcodes', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-shortcode';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Shortcodes());

        return parent::get_formatters($components, $config, $formatters);
    }

}