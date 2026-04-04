<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Comment\Property;
use AC\Formatter\WordCount;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class WordCountFactory extends BaseColumnFactory
{

    private BeforeAfter $before_after_factory;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        BeforeAfter $before_after_factory
    ) {
        parent::__construct($default_settings_builder);

        $this->before_after_factory = $before_after_factory;
    }

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

        $formatters->prepend(new WordCount());
        $formatters->prepend(new Property('comment_content'));

        return $formatters;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->before_after_factory->create($config),
        ]);
    }

}