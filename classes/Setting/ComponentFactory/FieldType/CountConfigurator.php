<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Setting\ComponentFactory\FieldTypeBuilder;

// TODO Should this be part of a field type setting?
class CountConfigurator implements FieldTypeConfigurator
{

    public const TYPE = 'count';

    public function configure(FieldTypeBuilder $builder): void
    {
        $builder->add_option(self::TYPE, __('Number of Fields', 'codepress-admin-columns'), 'multiple');
    }
}