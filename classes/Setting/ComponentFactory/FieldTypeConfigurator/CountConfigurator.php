<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

class CountConfigurator implements FieldTypeConfigurator
{
    public const TYPE = 'count';

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder->add_option(self::TYPE, __('Number of Fields', 'codepress-admin-columns'), 'multiple');
    }
}
