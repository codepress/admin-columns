<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Comment\Property;
use AC\Formatter\WordCount;
use AC\FormatterCollection;
use AC\Setting\Config;

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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new Property('comment_content'));
        $formatters->add(new WordCount());

        return $formatters;
    }

}