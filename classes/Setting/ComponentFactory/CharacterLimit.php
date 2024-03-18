<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;
use AC\Setting\Formatter;
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

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        $formatters->add(new Formatter\CharacterLimit((int)$config->get('character_limit')));

        return $formatters;
    }

    public function create(Config $config, Specification $conditions = null): Component
    {
        $limit = $config->has('character_limit') ? (int)$config->get('character_limit') : 20;

        $builder = (new ComponentBuilder())
            ->set_label(__('Character Limit', 'codepress-admin-columns'))
            ->set_description(
                sprintf(
                    '%s <em>%s</em>',
                    __('Maximum number of characters', 'codepress-admin-columns'),
                    __('Leave empty for no limit', 'codepress-admin-columns')
                )
            )
            ->set_input(
                Number::create_single_step(
                    'character_limit',
                    0,
                    null,
                    $limit,
                    null,
                    null,
                    __('Characters', 'codepress-admin-columns')
                )
            )
            ->set_formatter(new Formatter\CharacterLimit((int)$config->get('character_limit')));

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}