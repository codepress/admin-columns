<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

/**
 * Counts the values stored under one meta key. The formatters come from the column factory,
 * which reads every row for the key; counting the single value this component would resolve
 * gives a different, wrong answer.
 */
class CountConfigurator implements FieldTypeConfigurator
{
    public const TYPE = 'count';

    public function get_type(): string
    {
        return self::TYPE;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder->add_option(self::TYPE, __('Number of Values', 'codepress-admin-columns'), 'multiple');
    }
}
