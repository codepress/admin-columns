<?php

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AC\Formatter\Post\PostContent());
        $formatters->add(new AC\Formatter\WordCount());

        return $formatters;
    }

}