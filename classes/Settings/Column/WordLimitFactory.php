<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\AttributeCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input\Number;
use AC\Setting\Formatter;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

final class WordLimitFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Setting
    {
        $name = 'excerpt_length';
        $word_limit = (int)$config->get($name, 20);

        $input = Number::create_single_step(
            $name,
            0,
            null,
            $word_limit,
            null,
            null,
            __('Words', 'codepress-admin-columns')
        );

        $attributes = AttributeCollection::from_array([
            'description' => sprintf(
                '%s <em>%s</em>',
                __('Maximum number of words', 'codepress-admin-columns'),
                __('Leave empty for no limit', 'codepress-admin-columns')
            ),
        ]);

        return new Setting(
            'row',
            __('Word Limit', 'codepress-admin-columns'),
            $attributes,
            $input,
            $specification,
            new Formatter\WordLimit($word_limit)
        );
    }

}