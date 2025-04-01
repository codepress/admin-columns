<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class WordCountFactory extends ColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-word_count';
    }

    public function get_label(): string
    {
        return __('Word Count', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Formatter\Post\PostContent());
        $formatters->add(new Formatter\WordCount());

        return $formatters;
    }

}