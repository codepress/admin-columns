<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

/**
 * Counts how many times the meta key occurs. The formatters come from the column factory,
 * which is the only place that knows the meta type to read from.
 */
class MetaCountConfigurator implements FieldTypeConfigurator
{
    public const TYPE = 'meta_count';

    public function get_type(): string
    {
        return self::TYPE;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder->add_option(self::TYPE, __('Number of Fields', 'codepress-admin-columns'), 'multiple');
    }
}
