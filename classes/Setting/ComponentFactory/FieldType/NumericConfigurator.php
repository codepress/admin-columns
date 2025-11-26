<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldTypeBuilder;

class NumericConfigurator implements FieldTypeConfigurator
{

    public const TYPE = 'numeric';

    private ComponentFactory\NumberFormat $number_format;

    public function __construct(ComponentFactory\NumberFormat $number_format)
    {
        $this->number_format = $number_format;
    }

    public function configure(FieldTypeBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Number', 'codepress-admin-columns'), 'basic')
            ->add_child_component(
                $this->number_format,
                StringComparisonSpecification::equal(self::TYPE)
            );
    }
}