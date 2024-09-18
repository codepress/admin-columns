<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;
use AC\Setting\FormatterCollection;

final class CharacterLimit extends Builder
{

    protected function get_label(Config $config): ?string
    {
        return __('Character Limit', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return sprintf(
            '%s <em>%s</em>',
            __('Maximum number of characters', 'codepress-admin-columns'),
            __('Leave empty for no limit', 'codepress-admin-columns')
        );
    }

    protected function get_input(Config $config): ?Input
    {
        $limit = $config->has('character_limit') ? (int)$config->get('character_limit') : 20;

        return Number::create_single_step(
            'character_limit',
            0,
            null,
            $limit,
            null,
            null,
            __('Characters', 'codepress-admin-columns')
        );
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        $formatters->add(new AC\Value\Formatter\CharacterLimit((int)$config->get('character_limit')));
    }

}