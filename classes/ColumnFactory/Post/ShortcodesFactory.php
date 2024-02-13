<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Post\Shortcodes;

class ShortcodesFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Shortcodes', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-shortcode';
    }

    protected function create_formatter_builder(ComponentCollection $components): Formatter\AggregateBuilder
    {
        return parent::create_formatter_builder($components)->add(new Shortcodes());
    }

}