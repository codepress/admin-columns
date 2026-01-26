<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Post\PostContent;
use AC\Formatter\Post\Shortcodes;
use AC\FormatterCollection;
use AC\Setting\Config;

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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new PostContent());
        $formatters->add(new Shortcodes());

        return $formatters;
    }

}