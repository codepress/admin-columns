<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Comment\Property;
use AC\Setting\Formatter\WordCount;

class WordCountFactory extends ColumnFactory
{

    protected function get_label(): string
    {
        return __('Word Count', 'codepress-admin-columns');
    }

    public function get_type(): string
    {
        return 'column-word_count';
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new Property('comment_content'))
                     ->add(new WordCount());
    }

}