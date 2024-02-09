<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Config;
use AC\Setting\ComponentCollection;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

final class StringLimitFactory implements SettingFactory
{

    private $character_limit_factory;

    private $word_limit_factory;

    public function __construct(
        CharacterLimitFactory $character_limit_factory,
        WordLimitFactory $word_limit_factory
    ) {
        $this->character_limit_factory = $character_limit_factory;
        $this->word_limit_factory = $word_limit_factory;
    }

    public function create(Config $config, Specification $specification = null): Component
    {
        return new StringLimit(
            $config->get('string_limit') ?: '',
            new ComponentCollection([
                $this->character_limit_factory->create(
                    $config,
                    StringComparisonSpecification::equal('character_limit')
                ),
                $this->word_limit_factory->create(
                    $config,
                    StringComparisonSpecification::equal('excerpt_length')
                ),
            ]),
            $specification
        );
    }

}