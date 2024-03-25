<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

final class WordLimit extends Builder
{

    protected function get_label(Config $config): ?string
    {
        return __('Word Limit', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return sprintf(
            '%s <em>%s</em>',
            __('Maximum number of words', 'codepress-admin-columns'),
            __('Leave empty for no limit', 'codepress-admin-columns')
        );
    }

    protected function get_input(Config $config): Input
    {
        $name = 'word_limit';

        return Number::create_single_step(
            $name,
            0,
            null,
            (int)$config->get($name, 20),
            null,
            null,
            __('Words', 'codepress-admin-columns')
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        $formatters->add(
            new Formatter\WordLimit((int)$this->get_input($config)->get_value())
        );

        return $formatters;
    }

}