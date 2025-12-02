<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;
use AC\Setting\ComponentFactory\StringLimit;

class TextConfigurator implements FieldTypeConfigurator
{

    private StringLimit $string_limit;

    public function __construct(StringLimit $string_limit)
    {
        $this->string_limit = $string_limit;
    }

    public const TYPE = 'string';

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Text', 'codepress-admin-columns'), 'basic')
            ->add_child_component(
                $this->string_limit,
                StringComparisonSpecification::equal(self::TYPE)
            );
    }
}