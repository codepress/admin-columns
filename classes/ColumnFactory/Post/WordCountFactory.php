<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Formatter\Post\PostContent());
        $formatters->add(new Formatter\WordCount());
    }

}