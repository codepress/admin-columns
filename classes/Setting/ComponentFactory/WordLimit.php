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

    // TODO David decide if this shortcut is worth it Builder
    private const NAME = 'word_limit';

    private function get_value_word_limit(Config $config): int
    {
        return (int)$config->get(self::NAME, 20);
    }

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

    protected function get_input(Config $config): ?Input
    {
        return Number::create_single_step(
            self::NAME,
            0,
            null,
            $this->get_value_word_limit($config),
            null,
            null,
            __('Words', 'codepress-admin-columns')
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        $formatters->add(
            new Formatter\CharacterLimit($this->get_value_word_limit($config))
        );

        return $formatters;
    }

}