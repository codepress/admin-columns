<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\Number;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\ReadingTime;

final class WordsPerMinute implements ComponentFactory
{

    private const WORDS_PER_MINUTE = 'words_per_minute';

    public function create(Config $config, Specification $conditions = null): Component
    {
        $words_per_minute = (int)$config->get(self::WORDS_PER_MINUTE, 200);

        return new Component(
            __('Words per minute', 'codepress-admin-columns'),
            __(
                'Estimated reading time in words per minute.',
                'codepress-admin-columns'
            ),
            Number::create_single_step(
                self::WORDS_PER_MINUTE,
                0,
                null,
                $words_per_minute
            ),
            $conditions,
            new FormatterCollection([
                new ReadingTime($words_per_minute),
            ])

        );
    }

}