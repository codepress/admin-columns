<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Comment\Property;
use AC\Setting\Formatter\WordCount;
use AC\Setting\FormatterCollection;

class WordCountFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('Word Count', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-word_count';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Property('comment_content'));
        $formatters->add(new WordCount());

        return parent::get_formatters($components, $config, $formatters);
    }
}