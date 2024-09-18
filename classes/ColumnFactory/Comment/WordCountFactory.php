<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\Property;
use AC\Value\Formatter\WordCount;

class WordCountFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Word Count', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-word_count';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Property('comment_content'));
        $formatters->add(new WordCount());
    }

}