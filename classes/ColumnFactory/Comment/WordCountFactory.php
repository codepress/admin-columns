<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\Property;
use AC\Value\Formatter\WordCount;

class WordCountFactory extends ColumnFactory
{

    public function get_label(): string
    {
        return __('Word Count', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-word_count';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new Property('comment_content'));
        $formatters->add(new WordCount());

        return $formatters;
    }

}