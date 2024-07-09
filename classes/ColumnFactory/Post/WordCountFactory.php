<?php

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class WordCountFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-word_count';
    }

    public function get_label(): string
    {
        return __('Word Count', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AC\Value\Formatter\Post\PostContent());
        $formatters->add(new AC\Value\Formatter\WordCount());

        return parent::get_formatters($components, $config, $formatters);
    }

}