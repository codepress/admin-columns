<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class StringLimit extends Builder
{

    private const OPTION_CHARACTER_LIMIT = 'character_limit';
    private const OPTION_EXCERPT_LENGTH = 'excerpt_length';

    private CharacterLimit $character_limit;

    private WordLimit $word_limit;

    public function __construct(CharacterLimit $character_limit, WordLimit $word_limit)
    {
        $this->character_limit = $character_limit;
        $this->word_limit = $word_limit;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Text Limit', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        $name = 'string_limit';

        return OptionFactory::create_select(
            $name,
            OptionCollection::from_array([
                ''                           => __('No limit', 'codepress-admin-columns'),
                self::OPTION_CHARACTER_LIMIT => __('Character Limit', 'codepress-admin-columns'),
                self::OPTION_EXCERPT_LENGTH  => __('Word Limit', 'codepress-admin-columns'),
            ]),
            $config->get($name, '')
        );
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
                $this->character_limit->create(
                    $config,
                    StringComparisonSpecification::equal(self::OPTION_CHARACTER_LIMIT)
                ),
                $this->word_limit->create(
                    $config,
                    StringComparisonSpecification::equal(self::OPTION_EXCERPT_LENGTH)
                ),
            ])
        );
    }

}