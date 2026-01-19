<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\FormatterCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;

final class WordLimit extends BaseComponentFactory
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
        return Number::create_single_step(
            'excerpt_length',
            0,
            null,
            (int)$config->get('excerpt_length', 20),
            null,
            null,
            __('Words', 'codepress-admin-columns')
        );
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        $formatters->add(new AC\Formatter\WordLimit((int)$config->get('excerpt_length')));
    }

}